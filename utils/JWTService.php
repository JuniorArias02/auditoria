<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{

    /**
     * Funcion para generar token de acceso
     * @param array $datos
     * @return string
     */
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

    /**
     * funcion para varificar token de acceso
     * @param string $token
     * @return array|bool
     */
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

    /**
     * funcion para generar token unico
     * @param int $usuarioId
     * @param int $expSegundos
     * @return string
     */
    public static function generarTokenUnico(int $usuarioId, int $expSegundos = 600): string
    {
        $secret = $_ENV['JWT_SECRET'];

        $payload = [
            'iat' => time(),
            'exp' => time() + $expSegundos,
            'uso_unico' => true,
            'usuario_id' => $usuarioId
        ];
//                                                                                                                                                          
        return JWT::encode($payload, $secret, 'HS256');
    }

    /**
     * funcion para verificar token de uso unico
     * @param string $token
     * @return array|bool
     */
    public static function verificarTokenUnico(string $token): array|false
    {
        try {
            $secret = $_ENV['JWT_SECRET'];
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            $data = (array) $decoded;

            if (empty($data['uso_unico'])) {
                return false;
            }

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
