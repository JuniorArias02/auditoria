<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Dimensiones.php';

use App\Models\Dimensiones;

$id = $params[0] ?? null;
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'] ?? '';
$orden  = $data['orden'] ?? null;

if (!$id || !$nombre || $orden === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID, nombre y orden son obligatorios']);
    exit;
}

$dimension = new Dimensiones($pdo);
$ok = $dimension->actualizar($id, $nombre, $orden);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Dimensión actualizada correctamente']);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'No se encontró la dimensión']);
}