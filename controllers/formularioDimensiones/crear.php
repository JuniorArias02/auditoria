<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\FormularioDimensiones;
use App\Services\Logger;


try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents("php://input"), true);

    // Validar campos
    if (empty($data['formulario_auditoria_id']) || empty($data['dimension_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $formulario = new FormularioDimensiones($pdo);
    $creado = $formulario->crear($data['formulario_auditoria_id'], $data['dimension_id']);

    if ($creado) {
        echo json_encode(['success' => true, 'message' => 'formulario dimension creado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear el paciente']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $th->getMessage()
    ]);
    exit;
}
