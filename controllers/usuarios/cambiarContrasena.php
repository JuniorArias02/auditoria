<?php

use App\Bootstrap\App;
use App\Utils\JWTService;
use App\Services\EmailService;
use App\Models\Usuario;
use App\Services\Logger;

try {
	$pdo = App::getPdo();
	$body = json_decode(file_get_contents('php://input'), true);

	$token = $body['token_final'] ?? null;
	$newPassword = $body['new_password'] ?? null;

	if (!$token || !$newPassword) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Debe enviar token y nueva contraseña.'
		]);
		exit;
	}

	$data = JWTService::verificarTokenUnico($token);

	if (!$data || !isset($data['usuario_id'])) {
		http_response_code(401);
		echo json_encode([
			'success' => false,
			'message' => 'Token inválido o expirado.'
		]);
		exit;
	}

	$usuarioId = $data['usuario_id'];
	$usuarioModel = new Usuario($pdo);

	$usuario = $usuarioModel->obtener($usuarioId);

	if (!$usuario) {
		http_response_code(404);
		echo json_encode([
			'success' => false,
			'message' => 'Usuario no encontrado.'
		]);
		exit;
	}

	$actualizado = $usuarioModel->actualizarContrasena($usuarioId, $newPassword);

	if (!$actualizado) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'No se pudo actualizar la contraseña.'
		]);
		exit;
	}

	$emailResult = EmailService::send(
		$usuario['email'],
		'Cambio de contraseña exitoso',
		'cambioContrasena',
		[
			'nombre' => $usuario['nombre_completo'] ?? 'Usuario',
			'fecha' => date('d/m/Y H:i'),
			'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
			'dispositivo' => $_SERVER['HTTP_USER_AGENT'] ?? null
		]
	);


	if (!$emailResult['success']) {
		Logger::warning("No se pudo enviar correo de cambio de contraseña: " . $emailResult['error']);
	}

	$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
	Logger::info("Contraseña cambiada exitosamente para usuario ID: {$usuarioId} desde IP: {$ip}");

	echo json_encode([
		'success' => true,
		'message' => 'Contraseña actualizada exitosamente.'
	]);
} catch (\Throwable $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode([
		'success' => false,
		'message' => $th->getMessage()
	]);
}
