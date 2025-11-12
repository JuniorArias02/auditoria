<?php

use App\Bootstrap\App;
use App\Services\Logger;
use App\Models\ServicioAuditar;

try {
    $pdo = App::getPdo();

    $nombre = $params[0] ?? null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $servicioModel = new ServicioAuditar($pdo);
    $servicio = $servicioModel->obtenerPorNombre($nombre);

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
