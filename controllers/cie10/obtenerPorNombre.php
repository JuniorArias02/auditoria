<?php
use App\Bootstrap\App;
use App\Models\Cie10;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $pdo = App::getPdo();
    $nombre = $params[0] ?? null;

    if (!$nombre) {
        http_response_code(400);
        throw new \Exception('Nombre es requerido');
    }

    $Cie10Model = new Cie10($pdo);
    $cie10 = $Cie10Model->buscarPorCodigo($nombre);

    if ($cie10) {
        echo json_encode(['success' => true, 'data' => $cie10]);
    } else {
        http_response_code(404);
        throw new \Exception('Servicio no encontrado');
    }
} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
