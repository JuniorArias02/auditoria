<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Sedes.php';

use App\Models\Sede;

$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'] ?? '';
$tipo_modalidad = $data['tipo_modalidad'] ?? '';

if (!$nombre || !$tipo_modalidad) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$sede = new Sede($pdo);

if ($sede->crear($nombre, $tipo_modalidad)) {
    echo json_encode(['success' => true, 'message' => '✅ Sede creada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '❌ No se pudo crear la sede']);
}
