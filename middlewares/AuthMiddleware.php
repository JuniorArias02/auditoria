<?php
namespace App\Middlewares;

use App\Utils\JWTService;

class AuthMiddleware
{
    public static function check(): array
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
            exit;
        }

        $token = $matches[1];
        $userData = JWTService::verificarToken($token);

        if (!$userData) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token inválido o expirado']);
            exit;
        }

        return $userData;
    }
}
