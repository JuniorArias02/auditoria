<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;



try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido en la URL']);
        exit;
    }

    // Consultar auditoría
    $auditoria = new Auditoria($pdo);
    $resultado = $auditoria->obtenerPorId($id);

    if ($resultado) {
        echo json_encode(['success' => true, 'data' => $resultado]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Auditoría no encontrada']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
