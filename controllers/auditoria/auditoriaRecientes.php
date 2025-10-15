<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$auditoria = new Auditoria($pdo);
$auditorias = $auditoria->auditoriaRecientes();

echo json_encode([
	'success' => true,
	'data' => $auditorias
]);
