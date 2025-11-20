<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;
use App\Models\UserSetting;

try {

	$userData = AuthMiddleware::check();
	$usuarioId = $userData['id'];

	$app = new App();
	$pdo = $app->getPdo();
	$userSetting = new UserSetting($pdo);

	$config = $userSetting->obtenerPorUsuario($usuarioId);

	if (!$config) {
		$userSetting->crearInicial($usuarioId);
		$config = $userSetting->obtenerPorUsuario($usuarioId);
	}

	echo json_encode([
		'ok' => true,
		'settings' => $config
	]);
} catch (\Throwable $th) {

	Logger::error('Error obteniendo configuración: ' . $th->getMessage());

	echo json_encode([
		'ok' => false,
		'msg' => 'Error interno del servidor'
	]);
}
