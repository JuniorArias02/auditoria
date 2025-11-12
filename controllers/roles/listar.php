<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Roles;
use App\Services\Logger;


try {

	$userData = AuthMiddleware::check();
	$pdo = App::getPdo();

	$roles = new Roles($pdo);
	$rol = $roles->listar();

	echo json_encode($rol);
} catch (\Throwable $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode(['success' => false, 'message' => $th->getMessage()]);
	exit;
}
