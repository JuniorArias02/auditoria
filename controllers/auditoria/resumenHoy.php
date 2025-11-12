<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $fecha = $params[0] ?? null;
    if (!$fecha) {
        http_response_code(400);
        echo json_encode(['error' => 'Fecha requerida en la URL']);
        exit;
    }

    $auditoria = new Auditoria($pdo);
    $result = $auditoria->resumenHoy($fecha);

    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'No se encontró información para la fecha indicada']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
