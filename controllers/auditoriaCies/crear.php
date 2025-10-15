<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/AuditoriaCies.php';

$data = json_decode(file_get_contents("php://input"), true);
$cie10_id = $data['cie10_id'] ?? null;
$auditorias_id = $data['auditorias_id'] ?? null;

if (!$cie10_id || !$auditorias_id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El cie10_id y auditorias_id son requeridos.'
    ]);
    exit;
}

$model = new AuditoriaCies($pdo);
$creado = $model->crear($cie10_id, $auditorias_id);

if ($creado) {
    echo json_encode([
        'success' => true,
        'message' => 'Relación auditoría - CIE10 creada exitosamente.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear la relación auditoría - CIE10.'
    ]);
}
