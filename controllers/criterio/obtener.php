<?php
use App\Bootstrap\App;
use App\Models\Criterio;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID requerido"]);
        exit;
    }

    $criterio = new Criterio($pdo);
    $data = $criterio->obtenerPorId($id);

    if ($data) {
        echo json_encode(["success" => true, "data" => $data]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Criterio no encontrado"]);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
