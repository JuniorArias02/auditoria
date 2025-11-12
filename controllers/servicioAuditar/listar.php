<?php

use App\Bootstrap\App;
use App\Models\ServicioAuditar;
use App\Services\Logger;

try {
	$pdo = App::getPdo();

	$servicioModel = new ServicioAuditar($pdo);
	$result = $servicioModel->listar();

	echo json_encode(['success' => true, 'data' => $result]);
} catch (\Throwable $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode(['success' => false, 'message' => $th->getMessage()]);
	exit;
}
