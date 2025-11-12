<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $auditoria = new Auditoria($pdo);
    $resultado = $auditoria->metricasCalidadAuditoria();

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'data' => $resultado
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró información para la fecha indicada'
        ]);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
