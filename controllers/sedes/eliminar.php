<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Sedes;
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

    $sede = new Sedes($pdo);

    if ($sede->eliminar($id)) {
        echo json_encode(['success' => true, 'message' => 'Sede eliminada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la sede']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
