<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);
    $codigo = $data['codigo'] ?? null;
    $descripcion = $data['descripcion'] ?? null;

    if (!$id || !$codigo || !$descripcion) {
        http_response_code(400);
        echo json_encode(['error' => 'ID, código y descripción son requeridos.']);
        exit;
    }

    $c10 = new Cie10($pdo);
    if ($c10->actualizar($id, $codigo, $descripcion)) {
        echo json_encode(['success' => true, 'message' => 'Registro actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo actualizar el registro']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
