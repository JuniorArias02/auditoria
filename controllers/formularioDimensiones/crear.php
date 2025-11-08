<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../models/FormularioDimensiones.php';

use App\Models\FormularioDimensiones;

$data = json_decode(file_get_contents("php://input"), true);

// Validar campos
if (empty($data['formulario_auditoria_id']) || empty($data['dimension_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$formulario = new FormularioDimensiones($pdo);
$creado = $formulario->crear($data['formulario_auditoria_id'], $data['dimension_id']);

if ($creado) {
    echo json_encode(['success' => true, 'message' => 'formulario dimension creado exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear el paciente']);
}
