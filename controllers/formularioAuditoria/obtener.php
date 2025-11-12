<?php

use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID del formulario es requerido']);
        exit;
    }

    $formulario = new FormularioAuditoria($pdo);
    $registro = $formulario->obtenerPorId($id);

    if ($registro) {
        echo json_encode(['success' => true, 'data' => $registro]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
}
