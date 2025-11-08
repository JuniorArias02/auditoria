<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Profesional.php';

use App\Models\Auditoria;

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido en la URL']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$auditoria = new Auditoria($pdo);

if ($auditoria->actualizar($id, $data)) {
    echo json_encode(['message' => 'Auditoría actualizada correctamente']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se pudo actualizar la auditoría']);
}