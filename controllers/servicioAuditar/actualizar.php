<?php
use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Models\ServicioAuditar;
use App\Services\Logger;

try {
    $userData = AuthMiddleware::check();
    $pdo = App::getPdo();

    $id = $params[0] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? '';

    if (!$id || empty($nombre) || empty($descripcion)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID, nombre y descripción son requeridos']);
        exit;
    }

    $servicioModel = new ServicioAuditar($pdo);
    if ($servicioModel->actualizar($id, $nombre, $descripcion)) {
        echo json_encode(['success' => true, 'message' => 'Servicio actualizado con éxito']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el servicio']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    exit;
}
