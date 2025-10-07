<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Eps.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido']);
    exit;
}

$eps = new Eps($pdo);

if ($eps->eliminar($id)) {
    echo json_encode(['message' => 'EPS eliminada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo eliminar la EPS']);
}