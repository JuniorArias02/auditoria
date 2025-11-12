<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\FormularioAuditoria;
use App\Services\Logger;


try {

	$userData = AuthMiddleware::check();
	$pdo = App::getPdo();

	$formulario = new FormularioAuditoria($pdo);

	$response = $formulario->listar();

	echo json_encode(
		[
			'success' => true,
			'data' => $response
		]
	);
} catch (\Exception $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode([
		'success' => false,
		'message' => $th->getMessage()
	]);
}
