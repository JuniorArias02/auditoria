<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Usuario;
use App\Services\Logger;

try {
    // 1. Verificar autenticación
    $userData = AuthMiddleware::check();
    $userId = $userData['id'];

    $pdo = App::getPdo();
    $data = json_decode(file_get_contents('php://input'), true);

    $currentPassword = $data['current_password'] ?? '';
    $newPassword     = $data['new_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Debe proporcionar la contraseña actual y la nueva.']);
        exit;
    }

    // 2. Verificar que la contraseña actual sea correcta
    // Necesitamos instanciar User model y quizás reusar lógica de login o hacerlo manual
    // Dado que el modelo no tiene un método "verificarPassword" público simple, lo hacemos manual fetching usuario.
    $usuarioModel = new Usuario($pdo);

    // Obtenemos los datos, el método 'obtener' trae info básica, pero NO el password hash por seguridad (según vi en el archivo Usuario.php)
    // El método 'login' trae el password para verificar, pero aquí ya estamos logueados.
    // Vamos a tener que hacer query manual o agregar método al modelo. 
    // Para no tocar el modelo riesgosamente, haré un fetch manual del hash aquí o usaré una query simple.

    // Revisando Usuario.php: 'obtener' NO trae el password.
    // Haremos una query puntual para obtener el hash del usuario.
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userRow || !password_verify($currentPassword, $userRow['password'])) {
        http_response_code(400); // Bad Request o 401
        echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
        exit;
    }

    // 3. Actualizar la contraseña
    $ok = $usuarioModel->actualizarContrasena($userId, $newPassword);

    if ($ok) {
        // Log de auditoria
        Logger::info("El usuario ID $userId cambió su contraseña desde perfil.");
        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
