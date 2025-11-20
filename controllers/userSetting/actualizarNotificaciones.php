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

	$data = json_decode(file_get_contents("php://input"), true);

	if (!$data || !isset($data["notificaciones"])) {
		echo json_encode([
			"ok" => false,
			"msg" => "Falta el campo: notificaciones"
		]);
		exit;
	}

	$notificacionesJson = $data["notificaciones"];

	$resultado = $userSetting->actualizarNotificaciones($usuarioId, $notificacionesJson);

	echo json_encode([
		"ok" => $resultado,
		"msg" => $resultado ? "Notificaciones actualizadas" : "No se pudo actualizar",
	]);
} catch (\Throwable $th) {

	Logger::error("Error actualizando notificaciones: " . $th->getMessage());

	echo json_encode([
		"ok" => false,
		"msg" => "Error interno del servidor"
	]);
}
