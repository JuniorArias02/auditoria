<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

$auditoria = new Auditoria($pdo);
$result = $auditoria->listarAuditorias();

echo json_encode($result);
