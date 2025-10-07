<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Sedes.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

$sede = new Sede($pdo);

if ($sede->eliminar($id)) {
    echo json_encode(['success' => true, 'message' => 'Sede eliminada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la sede']);
}
