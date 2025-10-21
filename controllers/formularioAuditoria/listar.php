<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/FormularioAuditoria.php';

$formulario = new FormularioAuditoria($pdo);

$response = $formulario->listar();

echo json_encode(
	[
		'success' => true,
		'data' => $response
	]
);
