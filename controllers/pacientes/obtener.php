<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Pacientes;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID del paciente es requerido']);
        exit;
    }

    $paciente = new Pacientes($pdo);
    $registro = $paciente->obtener($id);

    if ($registro) {
        echo json_encode(['success' => true, 'data' => $registro]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
