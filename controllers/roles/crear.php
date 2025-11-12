<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Usuario;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();


    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre_completo'] ?? '';
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $rol = $data['rol'] ?? 'usuario';

    $usuario = new Usuario($pdo);
    $ok = $usuario->crear($nombre, $username, $email, $password, $rol_id);

    if ($ok) {
        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado con éxito'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo crear el usuario'
        ]);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
