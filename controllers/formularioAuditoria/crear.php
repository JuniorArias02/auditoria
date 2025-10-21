<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../models/FormularioAuditoria.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validar campos
if (empty($data['nombre_formulario']) || empty($data['descripcion'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$formulario = new FormularioAuditoria($pdo);
$creado = $formulario->crear($data['nombre_formulario'], $data['descripcion']);

if ($creado) {
    echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear el paciente']);
}
