<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;

$pdo = App::getPdo();

// Si quieres proteger con token
// $userData = AuthMiddleware::check();

$auditoria = new Auditoria($pdo);
$auditorias = $auditoria->auditoriaRecientes();

// Respuesta JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => $auditorias
]);
