<?php

use App\Bootstrap\App;
use App\Models\Usuario;
use App\Models\CodigosVerificacion;
use App\Utils\JWTService;
use App\Services\Logger;
use App\Services\EmailService;

try {
	$pdo = App::getPdo();
	$body = json_decode(file_get_contents('php://input'), true);

	$identificador = $body['identificador'] ?? null;

	if (!$identificador) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Debe enviar un identificador'
		]);
		exit;
	}

	$usuarioModel = new Usuario($pdo);
	$usuario = $usuarioModel->validarUsuario($identificador);

	if (!$usuario) {
		http_response_code(404);
		echo json_encode([
			'success' => false,
			'message' => 'Usuario no encontrado'
		]);
		exit;
	}

	$codigoModel = new CodigosVerificacion($pdo);
	$codigo = $codigoModel->generarCodigo($usuario['id']);
	$tokenUnico = JWTService::generarTokenUnico($usuario['id']);


	EmailService::send(
		$usuario['email'],
		'Código de verificación',
		'codigoAccess',
		[
			'nombre' => $usuario['username'],
			'codigo' => $codigo,
			'app' => 'AuditoriasIps',
			'url' => 'https://auditoriaips.clinicalhouse.co/'
		]
	);

	echo json_encode([
		'success' => true,
		'message' => 'Código enviado al correo',
		'token' => $tokenUnico
	]);

} catch (\Throwable $th) {
	Logger::exception($th);
	http_response_code($th->getCode() ?: 500);
	echo json_encode(['success' => false, 'message' => $th->getMessage()]);
	exit;
}
