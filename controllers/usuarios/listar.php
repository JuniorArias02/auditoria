<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Usuario.php';

$usuario = new Usuario($pdo);
$usuarios = $usuario->listar();

echo json_encode([
    'success' => true,
    'data' => $usuarios
]);
