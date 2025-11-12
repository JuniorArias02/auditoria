<?php

use App\Bootstrap\App;
use App\Models\Profesional;
use App\Services\Logger;

try {
	$pdo = App::getPdo();

	$profesional = new Profesional($pdo);
	$resultado = $profesional->obtenerTodos();

	echo json_encode(['success' => true, 'data' => $resultado]);
} catch (\Exception $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode(['success' => false, 'message' => $th->getMessage()]);
	exit;
}
