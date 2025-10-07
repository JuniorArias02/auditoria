<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/ServicioAuditar.php';

$servicioModel = new ServicioAuditar($pdo);
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'] ?? '';
$descripcion = $data['descripcion'] ?? '';

if (empty($nombre) || empty($descripcion)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
    exit;
}

if ($servicioModel->crear($nombre, $descripcion)) {
    echo json_encode(['success' => true, 'message' => 'Servicio creado con éxito']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear el servicio']);
}
