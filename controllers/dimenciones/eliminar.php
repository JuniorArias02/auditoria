<?php
use App\Bootstrap\App;
use App\Models\Dimensiones;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Falta el ID']);
        exit;
    }

    $dimension = new Dimensiones($pdo);
    $ok = $dimension->eliminar($id);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Dimensión eliminada correctamente']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Dimensión no encontrada']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
