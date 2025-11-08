<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

use App\Models\Auditoria;

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido en la URL']);
    exit;
}

$auditoria = new Auditoria($pdo);

if ($auditoria->eliminar($id)) {
    echo json_encode(['message' => 'Auditoría eliminada correctamente']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se pudo eliminar la auditoría']);
}