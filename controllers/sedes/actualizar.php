<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Sedes;
use App\Services\Logger;


try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $tipo_modalidad = $data['tipo_modalidad'] ?? '';

    if (!$id || !$nombre || !$tipo_modalidad) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $sede = new Sedes($pdo);

    if ($sede->actualizar($id, $nombre, $tipo_modalidad)) {
        echo json_encode(['success' => true, 'message' => '✅ Sede actualizada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '❌ No se pudo actualizar la sede']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
