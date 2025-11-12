<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\ServicioAuditar;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID es requerido']);
        exit;
    }

    $servicioModel = new ServicioAuditar($pdo);
    if ($servicioModel->eliminar($id)) {
        echo json_encode(['success' => true, 'message' => 'Servicio eliminado con éxito']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el servicio']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
