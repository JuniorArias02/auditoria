<?php

use App\Bootstrap\App;
use App\Models\CodigosVerificacion;
use App\Utils\JWTService;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $body = json_decode(file_get_contents('php://input'), true);

    $token = $body['token'] ?? null;
    $codigo = $body['codigo'] ?? null;

    // Validar campos
    if (!$token || !$codigo) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Debe enviar token y código.'
        ]);
        exit;
    }


    $data = JWTService::verificarTokenUnico($token);

    if (!$data) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token inválido o expirado.'
        ]);
        exit;
    }

    $usuarioId = $data['usuario_id'];

    // Validar código
    $codigosModel = new CodigosVerificacion($pdo);
    $valido = $codigosModel->validarCodigo($usuarioId, $codigo);

    if (!$valido) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Código inválido o expirado.'
        ]);
        exit;
    }

    // Marcar código como usado
    $codigosModel->marcarUsado($usuarioId, $codigo);

    $tokenFinal = JWTService::generarTokenUnico($usuarioId);


    echo json_encode([
        'success' => true,
        'message' => 'Código validado correctamente.',
        'token' => $tokenFinal
    ]);
} catch (\Throwable $th) {
    Logger::exception($th);
    $httpCode = ($th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
    http_response_code($httpCode);
    echo json_encode([
        'success' => false,
        'message' => $th->getMessage(),
        'error' => $_ENV['APP_ENV'] === 'development' ? $th->getTrace() : null
    ]);
}
