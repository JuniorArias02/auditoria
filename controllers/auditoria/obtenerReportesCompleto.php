<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Services\Logger;


try {

    $pdo = App::getPdo();

    $auditoria = new Auditoria($pdo);
    $result = $auditoria->obtenerReportesCompleto();

    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Auditoría no encontrada']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
