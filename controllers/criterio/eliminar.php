<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Criterio.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID requerido"]);
    exit;
}

$criterio = new Criterio($pdo);
if ($criterio->eliminar($id)) {
    echo json_encode(["message" => "Criterio eliminado correctamente"]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "No se pudo eliminar el criterio"]);
}