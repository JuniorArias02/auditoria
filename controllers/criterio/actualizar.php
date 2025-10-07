<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$id = $params[0] ?? null;
$input = json_decode(file_get_contents("php://input"), true);

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID requerido"]);
    exit;
}

$dimension_id = $input['dimension_id'] ?? null;
$descripcion  = $input['descripcion'] ?? '';
$orden        = $input['orden'] ?? null;

$criterio = new Criterio($pdo);
if ($criterio->actualizar($id, $dimension_id, $descripcion, $orden)) {
    echo json_encode(["message" => "Criterio actualizado correctamente"]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "No se pudo actualizar el criterio"]);
}