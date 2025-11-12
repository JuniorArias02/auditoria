<?php

use App\Bootstrap\App;
use App\Models\Sedes;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $nombre = $params[0] ?? null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $SedeModel = new Sedes($pdo);
    $sede = $SedeModel->obtenerPorNombre($nombre);

    if ($sede) {
        echo json_encode(['success' => true, 'data' => $sede]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sede no encontrado']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
