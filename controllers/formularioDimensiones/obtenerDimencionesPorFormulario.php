<?php
use App\Bootstrap\App;
use App\Models\FormularioDimensiones;
use App\Services\Logger;


try {
    $pdo = App::getPdo();
    
    $formulario_auditoria_id = $params[0] ?? null;

    if (!$formulario_auditoria_id) {
        throw new \Exception('Falta el parámetro formulario_auditoria_id', 400);
    }

    $model = new FormularioDimensiones($pdo);
    $data = $model->listarPorFormulario($formulario_auditoria_id);

    if (isset($data['error'])) {
        throw new \Exception($data['error'], 500);
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
