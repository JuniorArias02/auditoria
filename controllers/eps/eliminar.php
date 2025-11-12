<?php
use App\Bootstrap\App;
use App\Models\Eps;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }

    $eps = new Eps($pdo);
    $ok = $eps->eliminar($id);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'EPS eliminada correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la EPS']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
