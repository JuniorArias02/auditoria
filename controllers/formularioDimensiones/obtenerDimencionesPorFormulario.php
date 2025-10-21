<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../models/FormularioDimensiones.php';

$formulario_auditoria_id = $params[0];

if (!$formulario_auditoria_id) {
	http_response_code(400);
	echo json_encode(['error' => 'Falta el parámetro formulario_auditoria_id']);
	exit;
}

try {
	$model = new FormularioDimensiones($pdo);
	$data = $model->listarPorFormulario($formulario_auditoria_id);

	if (isset($data['error'])) {
		http_response_code(500);
		echo json_encode(['error' => $data['error']]);
		exit;
	}

	echo json_encode([
		'success' => true,
		'data' => $data
	]);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['error' => $e->getMessage()]);
}
