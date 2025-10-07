<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Criterio.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID requerido"]);
    exit;
}

$criterio = new Criterio($pdo);
$data = $criterio->obtenerPorId($id);

if ($data) {
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Criterio no encontrado"]);
}