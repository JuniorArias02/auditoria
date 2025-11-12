<?php
use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new \Exception('JSON inválido', 400);
    }

    $formularioModel = new FormularioAuditoria($pdo);
    $result = $formularioModel->crearNuevoFormulario($input);

    if (isset($result['error'])) {
        throw new \Exception($result['error'], 500);
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
