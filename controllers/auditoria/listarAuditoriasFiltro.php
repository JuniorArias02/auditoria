<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$auditoria = new Auditoria($pdo);
$result = $auditoria->listarAuditoriasFiltro($_GET['busqueda'] ?? null, $_GET['clasificacion'] ?? null, $_GET['fecha_inicio'] ?? null, $_GET['fecha_fin'] ?? null);
echo json_encode($result);
