<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Respuesta;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents('php://input'), true);


    $respuesta = new Respuesta($pdo);

    if (
        empty($data['auditoria_id']) ||
        empty($data['criterio_id']) ||
        !isset($data['puntaje'])
    ) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Faltan campos requeridos: auditoria_id, criterio_id o puntaje.'
        ]);
        exit;
    }

    $creado = $respuesta->crear(
        $data['auditoria_id'],
        $data['criterio_id'],
        $data['puntaje'],
        $data['observaciones'] ?? null
    );

    if ($creado) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Respuesta creada exitosamente.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo crear la respuesta.'
        ]);
    }
} catch (Exception $e) {
    Logger::exception($e);
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
