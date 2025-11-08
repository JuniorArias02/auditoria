<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Eps.php';

use App\Models\Eps;

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['nombre'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El campo nombre es requerido']);
    exit;
}

$eps = new Eps($pdo);

if ($eps->crear($data['nombre'])) {
    echo json_encode(['message' => 'EPS creada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear la EPS']);
}