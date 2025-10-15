<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

$paciente = new Pacientes($pdo);
$lista = $paciente->listar();

echo json_encode(['success' => true, 'data' => $lista]);
