<?php
use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;

    if (!$id) {
        throw new \Exception('ID del formulario es requerido', 400);
    }

    $model = new FormularioAuditoria($pdo);
    $formularios = $model->obtenerFormularioCompleto($id);

    if (!$formularios) {
        throw new \Exception('Formulario no encontrado', 404);
    }

    echo json_encode([
        'success' => true,
        'data' => $formularios
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
