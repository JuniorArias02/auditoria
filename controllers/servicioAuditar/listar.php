<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/ServicioAuditar.php';

use App\Models\ServicioAuditar;

$servicioModel = new ServicioAuditar($pdo);
$result = $servicioModel->listar();

echo json_encode(['success' => true, 'data' => $result]);
