<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Cie10.php';

$c10 = new Cie10($pdo);
$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido']);
    exit;
}

if ($c10->eliminar($id)) {
    echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo eliminar el registro']);
}