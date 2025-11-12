<?php
use App\Bootstrap\App;
use App\Models\Pacientes;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $nombre = $params[0] ?? null;

    if (!$nombre) {
        throw new \Exception('Nombre es requerido', 400);
    }

    $PacientesModel = new Pacientes($pdo);
    $paciente = $PacientesModel->buscarPorNombreOCedula($nombre);

    if (!$paciente) {
        throw new \Exception('Paciente no encontrado', 404);
    }

    echo json_encode([
        'success' => true,
        'data' => $paciente
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
