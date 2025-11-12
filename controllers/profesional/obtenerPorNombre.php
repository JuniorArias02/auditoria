<?php

use App\Bootstrap\App;
use App\Models\Profesional;
use App\Services\Logger;

try {

    $pdo = App::getPdo();

    $nombre = $params[0] ?? null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $ProfesionalModel = new Profesional($pdo);
    $profesional = $ProfesionalModel->buscarPorNombreOCedula($nombre);

    if ($profesional) {
        echo json_encode(['success' => true, 'data' => $profesional]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'profesional no encontrado']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
