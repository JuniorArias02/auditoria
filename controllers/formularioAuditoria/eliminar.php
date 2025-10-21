<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/FormularioAuditoria.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID del paciente es requerido']);
    exit;
}

$formulario = new FormularioAuditoria($pdo);
$eliminado = $formulario->eliminar($id);

if ($eliminado) {
    echo json_encode(['success' => true, 'message' => 'Paciente eliminado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el paciente']);
}
