<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $c10 = new Cie10($pdo);

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        throw new \Exception('ID requerido');
    }

    $registro = $c10->obtenerPorId($id);

    if ($registro) {
        echo json_encode(['success' => true, 'data' => $registro]);
    } else {
        http_response_code(404);
        throw new \Exception('Registro no encontrado');
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
