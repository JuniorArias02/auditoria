<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Respuesta.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validar datos
if (
    empty($data['auditoria_id']) ||
    empty($data['criterio_id']) ||
    !isset($data['puntaje'])
) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Faltan campos requeridos: auditoria_id, criterio_id o puntaje.'
    ]);
    exit;
}

try {
    $respuesta = new Respuesta($pdo);

    $creado = $respuesta->crear(
        $data['auditoria_id'],
        $data['criterio_id'],
        $data['puntaje'],
        $data['observaciones'] ?? null
    );

    if ($creado) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Respuesta creada exitosamente.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo crear la respuesta.'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
