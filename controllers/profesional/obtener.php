<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Profesional.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

$profesional = new Profesional($pdo);
$resultado = $profesional->obtenerPorId($id);

if ($resultado) {
    echo json_encode(['success' => true, 'data' => $resultado]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Profesional no encontrado']);
}