<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $auditoria = new Auditoria($pdo);
    $resultado = $auditoria->listarAuditorias();

    echo json_encode([
        'success' => true,
        'data' => $resultado
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
