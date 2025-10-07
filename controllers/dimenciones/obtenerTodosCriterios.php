<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../repositories/DimensionesRepository.php';

$dimension = new DimensionesRepository($pdo);
$data = $dimension->obtenerTodosLosCriterios();

echo json_encode($data);
