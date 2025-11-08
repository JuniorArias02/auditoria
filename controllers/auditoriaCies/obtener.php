<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Cie10.php';

use App\Models\Cie10;

$c10 = new Cie10($pdo);
$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido']);
    exit;
}

$registro = $c10->obtenerPorId($id);

if ($registro) {
    echo json_encode(['success' => true, 'data' => $registro]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Registro no encontrado']);
}