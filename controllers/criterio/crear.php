<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Criterio.php';

$input = json_decode(file_get_contents("php://input"), true);
$dimension_id = $input['dimension_id'] ?? null;
$descripcion  = $input['descripcion'] ?? '';
$orden        = $input['orden'] ?? null;

if (!$dimension_id || !$descripcion) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan campos obligatorios"]);
    exit;
}

$criterio = new Criterio($pdo);
$id = $criterio->crear($dimension_id, $descripcion, $orden);

if ($id) {
    echo json_encode(["message" => "Criterio creado correctamente", "id" => $id]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo crear el criterio"]);
}