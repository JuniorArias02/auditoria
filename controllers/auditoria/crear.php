<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$data = json_decode(file_get_contents('php://input'), true);

$auditoria = new Auditoria($pdo);

if ($auditoria->crear($data)) {
    echo json_encode(['message' => 'Auditoría creada correctamente']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se pudo crear la auditoría']);
}