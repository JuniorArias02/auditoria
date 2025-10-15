<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$fecha = $params[0] ?? null;

if (!$fecha) {
    http_response_code(400);
    echo json_encode(['error' => 'Fecha requerida en la URL']);
    exit;
}

$auditoria = new Auditoria($pdo);
$result = $auditoria->resumenHoy($fecha);

if ($result) {
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontró información para la fecha indicada']);
}
