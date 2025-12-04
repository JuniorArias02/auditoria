<?php

use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $fechaInicio = $_GET['fecha_inicio'] ?? null;
    $fechaFin    = $_GET['fecha_fin'] ?? null;

    if (!$fechaInicio || !$fechaFin) {
        throw new Exception("Faltan fechas", 400);
    }

    $auditoriaModel = new Auditoria($pdo);
    $resumen = $auditoriaModel->obtenerResumenAuditorias($fechaInicio, $fechaFin);

    echo json_encode(['success' => true, 'data' => $resumen]);
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
