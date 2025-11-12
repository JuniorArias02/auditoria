<?php
use App\Bootstrap\App;
use App\Models\Pacientes;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || empty($data['documento']) || empty($data['nombre_completo']) || empty($data['fecha_nacimiento']) || empty($data['eps_id'])) {
        http_response_code(400);
        throw new \Exception('ID y todos los campos son requeridos', 400);
    }

    $paciente = new Pacientes($pdo);
    $actualizado = $paciente->actualizar(
        $id,
        $data['documento'],
        $data['nombre_completo'],
        $data['fecha_nacimiento'],
        $data['eps_id']
    );

    if ($actualizado) {
        echo json_encode(['success' => true, 'message' => 'Paciente actualizado correctamente']);
    } else {
        throw new \Exception('Error al actualizar el paciente', 500);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
