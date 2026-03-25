<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Usuario;
use App\Services\Logger;

try {
    // 1. Verificar autenticación y obtener ID del usuario del token
    $userData = AuthMiddleware::check();
    $userId = $userData['id'];

    $pdo = App::getPdo();
    $data = json_decode(file_get_contents('php://input'), true);

    // 2. Validar datos de entrada
    $nombre   = $data['nombre_completo'] ?? '';
    $username = $data['username'] ?? '';
    $email    = $data['email'] ?? '';

    if (empty($nombre) || empty($username) || empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    // 3. Obtener el usuario actual para mantener validaciones (no cambiar rol ni activo)
    $usuarioModel = new Usuario($pdo);
    $currentUser = $usuarioModel->obtener($userId);

    if (!$currentUser) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }

    // (Opcional) Verificar si el username/email ya está en uso por OTRO usuario
    // Esta lógica podría estar dentro del modelo, pero por ahora asumimos que el update manejará duplicados si la db tiene constraints, 
    // o el método `actualizar` no hace esa chequeo. Idealmente validar antes.

    // 4. Actualizar
    // Nota: El método actualizar del modelo recibe: ($id, $nombre, $username, $email, $rol_id, $activo, $password = null)
    // Mantenemos el rol y estado activo actual del usuario.
    $ok = $usuarioModel->actualizar(
        $userId,
        $nombre,
        $username,
        $email,
        $currentUser['rol_id'],
        $currentUser['activo']
        // password null para no cambiarla aquí
    );

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Perfil actualizado con éxito.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el perfil.']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
