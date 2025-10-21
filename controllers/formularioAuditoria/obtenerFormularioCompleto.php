<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../models/FormularioAuditoria.php';
$id = $params[0] ?? null;
$model = new FormularioAuditoria($pdo);	

try {
    $formularios = $model->obtenerFormularioCompleto($id);
    echo json_encode($formularios);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
