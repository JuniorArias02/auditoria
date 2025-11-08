<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/ServicioAuditar.php';

use App\Models\ServicioAuditar;

$id = $params[0] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID es requerido']);
    exit;
}

$servicioModel = new ServicioAuditar($pdo);
if ($servicioModel->eliminar($id)) {
    echo json_encode(['success' => true, 'message' => 'Servicio eliminado con éxito']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el servicio']);
}
