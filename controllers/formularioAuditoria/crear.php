<?php
use App\Bootstrap\App;
use App\Models\FormularioAuditoria;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['nombre_formulario']) || empty($data['descripcion'])) {
        throw new \Exception('Todos los campos son obligatorios', 400);
    }

    $formulario = new FormularioAuditoria($pdo);
    $creado = $formulario->crear($data['nombre_formulario'], $data['descripcion']);

    if (!$creado) {
        throw new \Exception('Error al crear el formulario', 500);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Formulario creado exitosamente',
        'id' => $creado
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
