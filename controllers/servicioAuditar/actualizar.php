<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/ServicioAuditar.php';

$id = $params[0] ?? null;
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'] ?? '';
$descripcion = $data['descripcion'] ?? '';

if (!$id || empty($nombre) || empty($descripcion)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID, nombre y descripción son requeridos']);
    exit;
}

$servicioModel = new ServicioAuditar($pdo);
if ($servicioModel->actualizar($id, $nombre, $descripcion)) {
    echo json_encode(['success' => true, 'message' => 'Servicio actualizado con éxito']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el servicio']);
}
