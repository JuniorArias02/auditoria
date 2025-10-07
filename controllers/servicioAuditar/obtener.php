<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/ServicioAuditar.php';

$id = $params[0] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID es requerido']);
    exit;
}

$servicioModel = new ServicioAuditar($pdo);
$servicio = $servicioModel->obtenerPorId($id);

if ($servicio) {
    echo json_encode(['success' => true, 'data' => $servicio]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
}
