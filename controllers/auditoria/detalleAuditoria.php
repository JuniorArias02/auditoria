<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $userData = AuthMiddleware::check();

    $id = $params[0] ?? null;
    if (!$id) {
        throw new \Exception('ID de auditoría requerido', 400);
    }

    $auditoria = new Auditoria($pdo);
    $result = $auditoria->detalleAuditoria($id);

    if (!$result) {
        throw new \Exception('Auditoría no encontrada', 404);
    }

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
