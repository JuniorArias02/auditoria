<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$dias =  $params[0] ?? null;

$auditoriaModel = new Auditoria($pdo);
$resumen = $auditoriaModel->obtenerResumenAuditorias($dias);

header('Content-Type: application/json');
echo json_encode($resumen);
