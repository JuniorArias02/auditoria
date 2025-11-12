<?php
use App\Bootstrap\App;
use App\Models\Eps;
use App\Services\Logger;


try {
    $pdo = App::getPdo();
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['nombre'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El campo nombre es requerido']);
        exit;
    }

    $eps = new Eps($pdo);
    $id = $eps->crear($data['nombre']);

    if ($id) {
        echo json_encode(['success' => true, 'message' => 'EPS creada correctamente', 'id' => $id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la EPS']);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
