<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Profesional.php';

use App\Models\Profesional;

$profesional = new Profesional($pdo);
$resultado = $profesional->obtenerTodos();

echo json_encode(['success' => true, 'data' => $resultado]);