<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Cie10.php';

$c10 = new Cie10($pdo);
$lista = $c10->obtenerTodos();

echo json_encode([
    'success' => true,
    'data' => $lista
]);