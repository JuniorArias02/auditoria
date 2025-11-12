<?php
use App\Bootstrap\App;
use App\Models\Dimensiones;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre     = $data['nombre'] ?? null;
    $orden      = $data['orden'] ?? null;
    $porcentaje = $data['porcentaje'] ?? null;

    if (!$nombre || $orden === null || $porcentaje === null) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Nombre, orden y porcentaje son obligatorios'
        ]);
        exit;
    }

    $dimension = new Dimensiones($pdo);
    $id = $dimension->crear($nombre, $orden, $porcentaje);

    if ($id) {
        echo json_encode([
            'success' => true,
            'message' => 'Dimensión creada con éxito',
            'id' => $id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear la dimensión']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
