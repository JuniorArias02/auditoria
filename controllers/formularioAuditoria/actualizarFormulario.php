<?php
use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        throw new \Exception('Datos inválidos o faltan campos', 400);
    }

    $formularioModel = new FormularioAuditoria($pdo);
    $resultado = $formularioModel->actualizarFormularioCompleto($data);

    if (isset($resultado['error'])) {
        throw new \Exception($resultado['error'], 500);
    }

    echo json_encode(['success' => true, 'message' => 'Formulario actualizado correctamente']);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
