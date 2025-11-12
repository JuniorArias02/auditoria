<?php

use App\Bootstrap\App;
use App\Models\Sedes;
use App\Services\Logger;


try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }

    $sede = new Sedes($pdo);
    $data = $sede->obtenerPorId($id);

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => '❌ Sede no encontrada']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
