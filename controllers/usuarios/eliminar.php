<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Services\Logger;
use App\Models\Usuario;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();
    $permission = new Permission($userData);
    $permission->require('usuarios:eliminar');

    $usuario = new Usuario($pdo);


    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID requerido en la URL']);
        exit;
    }

    if ($usuario->eliminar($id)) {
        echo json_encode(['message' => 'Usuario eliminado correctamente']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No se pudo eliminar el usuario']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
