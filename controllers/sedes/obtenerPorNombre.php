<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Sedes.php';

use App\Models\Sede;

$nombre = $params[0] ?? null;
if (!$nombre) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
    exit;
}

$SedeModel = new Sede($pdo);
$sede = $SedeModel->obtenerPorNombre($nombre);

if ($sede) {
    echo json_encode(['success' => true, 'data' => $sede]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Sede no encontrado']);
}
