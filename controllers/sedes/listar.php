<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Sedes.php';

$sede = new Sede($pdo);
$sedes = $sede->obtenerTodos();

echo json_encode(['success' => true, 'data' => $sedes]);
