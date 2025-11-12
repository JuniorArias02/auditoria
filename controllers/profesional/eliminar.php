<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Profesional;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }

    $profesional = new Profesional($pdo);
    $eliminado = $profesional->eliminar($id);

    if ($eliminado) {
        echo json_encode(['success' => true, 'message' => 'Profesional eliminado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el profesional']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
