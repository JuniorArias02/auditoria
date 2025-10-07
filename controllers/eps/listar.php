<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Eps.php';

$eps = new Eps($pdo);
$result = $eps->obtenerTodos();

echo json_encode($result);