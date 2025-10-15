<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../utils/jwt.php';

$data = json_decode(file_get_contents('php://input'), true);

$identificador = $data['identificador'] ?? '';
$password      = $data['password'] ?? '';

$usuario = new Usuario($pdo);
$user = $usuario->login($identificador, $password);

if ($user) {
    $token = generarToken([
        'id'       => $user['id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'rol_id'   => $user['rol_id']
    ]);


    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'token'   => $token,
        'user'    => $user
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales incorrectas'
    ]);
}
