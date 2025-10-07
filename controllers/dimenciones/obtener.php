<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Dimensiones.php';

$id = $params[0] ?? null;


if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Falta el ID']);
    exit;
}

$dimension = new Dimensiones($pdo);
$result = $dimension->obtenerPorId($id);

if ($result) {
    echo json_encode(['success' => true, 'data' => $result]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Dimensión no encontrada']);
}