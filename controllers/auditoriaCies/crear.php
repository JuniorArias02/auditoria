<?php
use App\Bootstrap\App;
use App\Models\AuditoriaCies;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;

header('Content-Type: application/json');

try {
    $userData = AuthMiddleware::check();

    $pdo = App::getPdo();

    $data = json_decode(file_get_contents("php://input"), true);
    $cie10_id = $data['cie10_id'] ?? null;
    $auditorias_id = $data['auditorias_id'] ?? null;

    if (!$cie10_id || !$auditorias_id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'El cie10_id y auditorias_id son requeridos.'
        ]);
        exit;
    }

    // Crear relación
    $model = new AuditoriaCies($pdo);
    $creado = $model->crear($cie10_id, $auditorias_id);

    if ($creado) {
        echo json_encode([
            'success' => true,
            'message' => 'Relación auditoría - CIE10 creada exitosamente.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear la relación auditoría - CIE10.'
        ]);
    }

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
