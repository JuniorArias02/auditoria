<?php

use App\Bootstrap\App;
use App\Models\Pacientes;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $nombre = $params[0] ?? null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $PacientesModel = new Pacientes($pdo);
    $paciente = $PacientesModel->buscarPorNombreOCedula($nombre);

    if ($paciente) {
        echo json_encode(['success' => true, 'data' => $paciente]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'paciente no encontrado']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
