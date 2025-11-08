<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Roles.php';

use App\Models\Roles;

$roles = new Roles($pdo);
$rol = $roles->listar();

echo json_encode( $rol);
