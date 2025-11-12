<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    // Validar token
    $userData = AuthMiddleware::check();

    // Obtener PDO centralizado
    $pdo = App::getPdo();

    // Datos del request
    $data = json_decode(file_get_contents("php://input"), true);
    $codigo = $data['codigo'] ?? null;
    $descripcion = $data['descripcion'] ?? null;

    if (!$codigo || !$descripcion) {
        http_response_code(400);
        echo json_encode(['error' => 'El código y la descripción son requeridos.']);
        exit;
    }

    // Crear registro
    $c10 = new Cie10($pdo);
    $id = $c10->crear($codigo, $descripcion);

    if ($id) {
        echo json_encode([
            'success' => true,
            'message' => 'Registro creado correctamente',
            'id' => $id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo crear el registro.']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
