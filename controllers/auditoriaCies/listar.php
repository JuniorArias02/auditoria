<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $c10 = new Cie10($pdo);
    $lista = $c10->obtenerTodos();

    echo json_encode([
        'success' => true,
        'data' => $lista
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
