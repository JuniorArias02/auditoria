<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

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
