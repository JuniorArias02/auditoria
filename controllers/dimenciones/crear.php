<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Dimensiones.php';
use App\Models\Dimensiones;
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'];
$orden  = $data['orden'];
$porcentaje = $data['porcentaje'] ;

if (!$nombre || $orden === null || $porcentaje === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre y orden son obligatorios']);
    exit;
}

$dimension = new Dimensiones($pdo);
$id = $dimension->crear($nombre, $orden, $porcentaje);

if ($id) {
    echo json_encode([
        'success' => true,
        'message' => 'Dimensión creada con éxito',
        'id' => $id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al crear la dimensión']);
}