<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $dias = $params[0] ?? null;

    $auditoriaModel = new Auditoria($pdo);
    $resumen = $auditoriaModel->obtenerResumenAuditorias($dias);

    echo json_encode(['success' => true, 'data' => $resumen]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
