<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\ServicioAuditar;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $servicioModel = new ServicioAuditar($pdo);
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? '';

    if (empty($nombre) || empty($descripcion)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
        exit;
    }

    if ($servicioModel->crear($nombre, $descripcion)) {
        echo json_encode(['success' => true, 'message' => 'Servicio creado con éxito']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear el servicio']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
