<?php

use App\Bootstrap\App;
use App\Models\Eps;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $id = $params[0] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID requerido']);
        exit;
    }

    $eps = new Eps($pdo);
    $result = $eps->obtenerPorId($id);

    if ($result) {
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'EPS no encontrada']);
    }
} catch (\Throwable $th) {
    Logger::exception($th);
    http_response_code($th->getCode() ?: 500);
    echo json_encode(['error' => $th->getMessage()]);
}
