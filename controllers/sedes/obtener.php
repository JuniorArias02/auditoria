<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Sedes.php';

use App\Models\Sede;

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

$sede = new Sede($pdo);
$data = $sede->obtenerPorId($id);

if ($data) {
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '❌ Sede no encontrada']);
}
