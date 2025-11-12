<?php
use App\Bootstrap\App;
use App\Models\Criterio;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$id) {
        http_response_code(400);
        throw new \Exception("ID requerido");
    }

    $dimension_id = $input['dimension_id'] ?? null;
    $descripcion  = $input['descripcion'] ?? '';
    $orden        = $input['orden'] ?? null;

    $criterio = new Criterio($pdo);
    if ($criterio->actualizar($id, $dimension_id, $descripcion, $orden)) {
        echo json_encode(["success" => true, "message" => "Criterio actualizado correctamente"]);
    } else {
        http_response_code(400);
        throw new \Exception("No se pudo actualizar el criterio");
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
