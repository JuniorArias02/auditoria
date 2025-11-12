<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    public static function generarToken(array $datos): string
    {
        $secret = $_ENV['JWT_SECRET'];
        $exp = time() + ($_ENV['JWT_EXPIRE'] ?? 3600);

        $payload = [
            'iat' => time(),
            'exp' => $exp,
            'data' => $datos
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function verificarToken(string $token): array|false
    {
        try {
            $secret = $_ENV['JWT_SECRET'];
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array) $decoded->data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
