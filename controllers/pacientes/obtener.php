<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

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
