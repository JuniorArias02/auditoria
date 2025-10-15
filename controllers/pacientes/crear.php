<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validar campos
if (empty($data['documento']) || empty($data['nombre_completo']) || empty($data['fecha_nacimiento']) || empty($data['eps_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$paciente = new Pacientes($pdo);
$creado = $paciente->crear($data['documento'], $data['nombre_completo'], $data['fecha_nacimiento'], $data['eps_id']);

if ($creado) {
    echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear el paciente']);
}
