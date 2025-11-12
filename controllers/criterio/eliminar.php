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
        throw new \Exception("ID requerido");
    }

    $criterio = new Criterio($pdo);
    if ($criterio->eliminar($id)) {
        echo json_encode([
            "success" => true,
            "message" => "Criterio eliminado correctamente"
        ]);
    } else {
        http_response_code(400);
        throw new \Exception("No se pudo eliminar el criterio");
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
