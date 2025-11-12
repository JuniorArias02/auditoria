<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Usuario;
use App\Services\Logger;


try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido en la URL']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $nombre   = $data['nombre_completo'] ?? '';
    $username = $data['username'] ?? '';
    $email    = $data['email'] ?? '';
    $rol_id   = $data['rol'] ?? 2;
    $activo   = $data['activo'] ?? 1;
    $password = $data['password'] ?? null;

    $usuario = new Usuario($pdo);
    $ok = $usuario->actualizar($id, $nombre, $username, $email, $rol_id, $activo, $password);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el usuario']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
