<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Services\Logger;
use App\Models\Usuario;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    // Validar permisos
    $permission = new Permission($userData);
    $permission->require('usuarios:eliminar');

    $usuario = new Usuario($pdo);

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID requerido en la URL']);
        exit;
    }

    $ejecutorId = $userData['id'] ?? 'desconocido';

    if ($usuario->eliminar($id)) {

        Logger::info("Usuario ID $ejecutorId eliminó al usuario ID $id", "usuario");

        echo json_encode(['message' => 'Usuario eliminado correctamente']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No se pudo eliminar el usuario']);
    }

} catch (\Throwable $th) {

    Logger::exception($th, "usuario");

    http_response_code($th->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $th->getMessage()
    ]);
}
