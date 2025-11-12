<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Models\Usuario;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $permission = new Permission($userData);
    $permission->require('usuario:listar');
    $pdo = App::getPdo();

    $usuario = new Usuario($pdo);
    $usuarios = $usuario->listar();

    echo json_encode([
        'success' => true,
        'data' => $usuarios
    ]);
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
