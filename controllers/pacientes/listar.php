<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Pacientes;
use App\Services\Logger;

try {
	$userData = AuthMiddleware::check();
	$pdo = App::getPdo();
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
} catch (\Throwable $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode(['success' => false, 'message' => $th->getMessage()]);
	exit;
}
