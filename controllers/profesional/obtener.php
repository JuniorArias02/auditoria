<?php

use App\Bootstrap\App;
use App\Models\Profesional;
use App\Services\Logger;

try {

    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }

    $profesional = new Profesional($pdo);
    $resultado = $profesional->obtenerPorId($id);

    if ($resultado) {
        echo json_encode(['success' => true, 'data' => $resultado]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Profesional no encontrado']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
