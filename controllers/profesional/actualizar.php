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
    $permission->require('profesional:actualizar');


    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || empty($data['nombre']) || empty($data['cargo'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID y todos los campos son requeridos']);
        exit;
    }

    $profesional = new Profesional($pdo);
    $actualizado = $profesional->actualizar($id, $data['nombre'], $data['cedula'], $data['cargo']);

    if ($actualizado) {
        Logger::info("Profesional con ID $id actualizado por usuario {$userData['id']}");
        echo json_encode(['success' => true, 'message' => 'Profesional actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el profesional']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
