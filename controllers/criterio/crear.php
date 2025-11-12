<?php
use App\Bootstrap\App;
use App\Models\Criterio;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $input = json_decode(file_get_contents("php://input"), true);

    $dimension_id = $input['dimension_id'] ?? null;
    $descripcion  = $input['descripcion'] ?? '';
    $orden        = $input['orden'] ?? null;

    if (!$dimension_id || !$descripcion) {
        http_response_code(400);
        throw new \Exception("Faltan campos obligatorios");
    }

    $criterio = new Criterio($pdo);
    $id = $criterio->crear($dimension_id, $descripcion, $orden);

    if ($id) {
        echo json_encode([
            "success" => true,
            "message" => "Criterio creado correctamente",
            "id" => $id
        ]);
    } else {
        http_response_code(500);
        throw new \Exception("No se pudo crear el criterio");
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
