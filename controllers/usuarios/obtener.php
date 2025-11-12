<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;
use App\Models\Usuario;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID requerido en la URL'
        ]);
        exit;
    }

    $usuario = new Usuario($pdo);
    $user = $usuario->obtener($id);

    if ($user) {
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
