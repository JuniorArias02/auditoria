<?php
use App\Bootstrap\App;
use App\Repositories\DimensionesRepository;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $dimensionRepo = new DimensionesRepository($pdo);
    $data = $dimensionRepo->obtenerTodosLosCriterios();

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
