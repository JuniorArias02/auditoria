<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../utils/jwt.php';

use App\Models\Usuario;
use App\Services\EmailService;

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $identificador = $data['identificador'] ?? '';
    $password      = $data['password'] ?? '';

    $usuario = new Usuario($pdo);
    $user = $usuario->login($identificador, $password);

    if (!$user) {
        throw new Exception('Credenciales incorrectas', 401);
    }

    $token = generarToken([
        'id'       => $user['id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'rol_id'   => $user['rol_id']
    ]);

    // Enviar correo de bienvenida (opcional)
    EmailService::send(
        $user['email'],
        'Bienvenido de nuevo',
        'welcome',
        [
            'nombre' => $user['username'],
            'app' => 'AuditoriasIps',
            'url' => 'https://auditoriaips.clinicalhouse.co/'
        ]
    );

    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'token'   => $token,
        'user'    => $user
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
