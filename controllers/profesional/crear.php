<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Profesional.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['nombre']) || empty($data['cargo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$profesional = new Profesional($pdo);
$creado = $profesional->crear($data['nombre'], $data['cargo']);

if ($creado) {
    echo json_encode(['success' => true, 'message' => 'Profesional creado exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear el profesional']);
}