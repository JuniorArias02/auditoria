<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Models\Profesional;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $permission = new Permission($userData);
    $permission->require('profesional:crear');

    $data = json_decode(file_get_contents("php://input"), true);

    // Validación correcta
    if (empty($data['nombre']) || empty($data['cargo']) || empty($data['cedula'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios'
        ]);
        exit;
    }

    $profesional = new Profesional($pdo);
    $creado = $profesional->crear($data['nombre'], $data['cedula'], $data['cargo']);

    if ($creado) {

        Logger::info(
            "Profesional creado | Nombre: {$data['nombre']} | Cédula: {$data['cedula']} | Usuario ejecutor ID: {$userData['id']}",
            "profesional"
        );

        echo json_encode([
            'success' => true,
            'message' => 'Profesional creado exitosamente'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear el profesional'
        ]);
    }

} catch (\Throwable $th) {
    Logger::exception($th, "profesional");
    http_response_code($th->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $th->getMessage()
    ]);
    exit;
}
