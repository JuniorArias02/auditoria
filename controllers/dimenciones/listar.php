<?php
use App\Bootstrap\App;
use App\Models\Dimensiones;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $dimension = new Dimensiones($pdo);
    $result = $dimension->listar();

    echo json_encode([
        'success' => true,
        'data' => $result
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
