<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php'; // aquí ya debe venir $pdo
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../models/FormularioAuditoria.php';

use App\Models\FormularioAuditoria;

try {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos o faltan campos']);
        exit;
    }

    $formularioModel = new FormularioAuditoria($pdo);

    $resultado = $formularioModel->actualizarFormularioCompleto($data);

    if (isset($resultado['error'])) {
        http_response_code(500);
        echo json_encode(['error' => $resultado['error']]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Formulario actualizado correctamente']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
