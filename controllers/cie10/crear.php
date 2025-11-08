<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Cie10.php';

use App\Models\Cie10;

$c10 = new Cie10($pdo);

$data = json_decode(file_get_contents("php://input"), true);
$codigo = $data['codigo'] ?? null;
$descripcion = $data['descripcion'] ?? null;

if (!$codigo || !$descripcion) {
    http_response_code(400);
    echo json_encode(['error' => 'El código y la descripción son requeridos.']);
    exit;
}

$id = $c10->crear($codigo, $descripcion);

if ($id) {
    echo json_encode([
        'success' => true,
        'message' => 'Registro creado correctamente',
        'id' => $id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear el registro.']);
}