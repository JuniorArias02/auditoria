<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Dimensiones.php';

use App\Models\Dimensiones;

$dimension = new Dimensiones($pdo);
$result = $dimension->listar();

echo json_encode([
    'success' => true,
    'data' => $result
]);