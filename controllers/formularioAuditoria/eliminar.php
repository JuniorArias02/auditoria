<?php
use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;

    if (!$id) {
        throw new \Exception('ID del paciente es requerido', 400);
    }

    $formulario = new FormularioAuditoria($pdo);
    $eliminado = $formulario->eliminar($id);

    if (!$eliminado) {
        throw new \Exception('Error al eliminar el paciente', 500);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Paciente eliminado correctamente'
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
