<?php

use App\Bootstrap\App;
use App\Models\ServicioAuditar;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID es requerido']);
        exit;
    }

    $servicioModel = new ServicioAuditar($pdo);
    $servicio = $servicioModel->obtenerPorId($id);

    if ($servicio) {
        echo json_encode(['success' => true, 'data' => $servicio]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
