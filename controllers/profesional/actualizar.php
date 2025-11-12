<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Pacientes.php';

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Profesional;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || empty($data['nombre']) || empty($data['cargo'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID y todos los campos son requeridos']);
        exit;
    }

    $profesional = new Profesional($pdo);
    $actualizado = $profesional->actualizar($id, $data['nombre'], $data['cargo']);

    if ($actualizado) {
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
