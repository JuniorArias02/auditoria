<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Models\Pacientes;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $permission = new Permission($userData);
    $permission->require('paciente:eliminar');
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID del paciente es requerido']);
        exit;
    }

    $paciente = new Pacientes($pdo);
    $eliminado = $paciente->eliminar($id);

    if ($eliminado) {
        echo json_encode(['success' => true, 'message' => 'Paciente eliminado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el paciente']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
}
