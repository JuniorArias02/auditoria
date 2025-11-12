<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $userData = AuthMiddleware::check();


    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID requerido']);
        exit;
    }

    // Eliminar registro
    $c10 = new Cie10($pdo);
    if ($c10->eliminar($id)) {
        echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo eliminar el registro']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
