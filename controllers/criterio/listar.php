<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Criterio.php';

use App\Models\Criterio;

$criterio = new Criterio($pdo);
$data = $criterio->obtenerTodos();

echo json_encode($data);