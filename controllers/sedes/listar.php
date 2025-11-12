<?php
use App\Bootstrap\App;
use App\Models\Sedes;
use App\Services\Logger;

try {

	$pdo = App::getPdo();

	$sede = new Sedes($pdo);
	$sedes = $sede->obtenerTodos();

	echo json_encode(['success' => true, 'data' => $sedes]);
} catch (\Exception $th) {
	Logger::exception($th);
	http_response_code(500);
	echo json_encode(['success' => false, 'message' => 'Error del servidor']);
	exit;
}
