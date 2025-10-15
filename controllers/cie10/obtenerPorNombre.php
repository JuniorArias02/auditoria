<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Cie10.php';

$nombre = $params[0] ?? null;
if (!$nombre) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
    exit;
}

$Cie10Model = new Cie10($pdo);
$cie10 = $Cie10Model->buscarPorCodigo($nombre);

if ($cie10) {
    echo json_encode(['success' => true, 'data' => $cie10]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
}
