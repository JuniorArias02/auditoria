<?php
use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Permission;
use App\Models\Auditoria;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();
    $permission = new Permission($userData);
    $permission->require('auditoria:eliminar');

    $id = $params[0] ?? null;
    if (!$id) {
        throw new \Exception('ID de auditoría requerido', 400);
    }

    $auditoria = new Auditoria($pdo);
    $resultado = $auditoria->eliminar($id);

    if (!$resultado) {
        throw new \Exception('No se pudo eliminar la auditoría', 400);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Auditoría eliminada correctamente'
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
