<?php
use App\Bootstrap\App;
use App\Models\Criterio;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $criterio = new Criterio($pdo);
    $data = $criterio->obtenerTodos();

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
