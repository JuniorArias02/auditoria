<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Sedes;
use App\Services\Logger;



try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $tipo_modalidad = $data['tipo_modalidad'] ?? '';

    if (!$nombre || !$tipo_modalidad) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $sede = new Sedes($pdo);

    if ($sede->crear($nombre, $tipo_modalidad)) {
        echo json_encode(['success' => true, 'message' => '✅ Sede creada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '❌ No se pudo crear la sede']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
