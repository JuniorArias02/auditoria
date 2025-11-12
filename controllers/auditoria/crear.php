<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $userData = AuthMiddleware::check();


    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new \Exception('Datos inválidos o mal formateados', 400);
    }

    $auditoria = new Auditoria($pdo);
    $id = $auditoria->crear($data);

    if (!$id) {
        throw new \Exception('No se pudo crear la auditoría', 500);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Auditoría creada correctamente',
        'auditoria_id' => $id
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
