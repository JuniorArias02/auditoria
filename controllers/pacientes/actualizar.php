<?php
use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\Pacientes;
use App\Services\Logger;


try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    // Obtener datos enviados en JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar campos requeridos
    if (!$id || empty($data['documento']) || empty($data['nombre_completo']) || empty($data['fecha_nacimiento']) || empty($data['eps_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID y todos los campos son requeridos']);
        exit;
    }

    // Instanciar modelo
    $paciente = new Pacientes($pdo);

    // Actualizar paciente
    $actualizado = $paciente->actualizar(
        $id,
        $data['documento'],
        $data['nombre_completo'],
        $data['fecha_nacimiento'],
        $data['eps_id']
    );

    // Respuesta
    if ($actualizado) {
        echo json_encode(['success' => true, 'message' => 'Paciente actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el paciente']);
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
