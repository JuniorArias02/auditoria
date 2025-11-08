<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

use App\Models\Auditoria;

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos o mal formateados']);
    exit;
}

$auditoria = new Auditoria($pdo);

$id = $auditoria->crear($data);

if ($id) {
    echo json_encode([
        'success' => true,
        'message' => 'Auditoría creada correctamente',
        'auditoria_id' => $id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear la auditoría']);
}
