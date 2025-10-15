<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

$id = $params[0] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

if (!$id || empty($data['nombre']) || empty($data['cargo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID y todos los campos son requeridos']);
    exit;
}

$profesional = new Profesional($pdo);
$actualizado = $profesional->actualizar($id, $data['nombre'], $data['cargo']);

if ($actualizado) {
    echo json_encode(['success' => true, 'message' => 'Profesional actualizado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el profesional']);
}