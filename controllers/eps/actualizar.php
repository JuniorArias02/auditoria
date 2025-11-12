<?php
use App\Bootstrap\App;
use App\Models\Eps;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$id || empty($data['nombre'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID y nombre son requeridos']);
        exit;
    }

    $eps = new Eps($pdo);

    if ($eps->actualizar($id, $data['nombre'])) {
        echo json_encode(['success' => true, 'message' => 'EPS actualizada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la EPS']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
