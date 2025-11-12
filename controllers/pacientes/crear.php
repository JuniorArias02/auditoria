<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Models\Pacientes;
use App\Services\Logger;

try {

    $userData = AuthMiddleware::check();
    $permission = new Permission($userData);
    $permission->require('paciente:crear');
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents("php://input"), true);

    // Validar campos
    if (empty($data['documento']) || empty($data['nombre_completo']) || empty($data['fecha_nacimiento']) || empty($data['eps_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $paciente = new Pacientes($pdo);
    $creado = $paciente->crear($data['documento'], $data['nombre_completo'], $data['fecha_nacimiento'], $data['eps_id']);

    if ($creado) {
        echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear el paciente']);
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
