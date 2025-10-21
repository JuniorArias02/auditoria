<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

$pacienteModel = new Pacientes($pdo);

$query = $_GET['query'] ?? '';
$response = $pacienteModel->listar($query);

echo json_encode(
	[
		'success' => true,
		'data' => $response['data'],
		'total' => $response['total'],
	]
);
