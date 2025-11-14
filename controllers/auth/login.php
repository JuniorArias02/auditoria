<?php

use App\Bootstrap\App;
use App\Models\Usuario;
use App\Services\EmailService;
use App\Services\Logger;
use App\Utils\JWTService;

try {
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $identificador = $data['identificador'] ?? '';
    $password      = $data['password'] ?? '';

    $usuario = new Usuario($pdo);
    $user = $usuario->login($identificador, $password);

    if (!$user) {
        Logger::request("Login fallido | Identificador: $identificador", Logger::SECURITY);
        throw new \Exception('Credenciales incorrectas', 401);
    }

    $token = JWTService::generarToken([
        'id'       => $user['id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'rol_id'   => $user['rol_id']
    ]);

    EmailService::send(
        $user['email'],
        'Bienvenido de nuevo',
        'welcome',
        [
            'nombre' => $user['username'],
            'app'    => 'AuditoriasIps',
            'url'    => 'https://auditoriaips.clinicalhouse.co/'
        ]
    );

    Logger::request("Login exitoso | Usuario: {$user['username']}", Logger::SECURITY);

    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'token'   => $token,
        'user'    => $user
    ]);

} catch (\Exception $e) {

    Logger::exception($e, Logger::SECURITY);

    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
