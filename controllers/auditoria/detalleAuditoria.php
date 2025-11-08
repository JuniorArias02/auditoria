<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';

use App\Models\Auditoria;

$id = $params[0] ?? null;

if (!$id) {
	http_response_code(400);
	echo json_encode(['error' => 'ID de auditoría requerido']);
	exit;
}

$auditoria = new Auditoria($pdo);
$result = $auditoria->detalleAuditoria($id);

if ($result) {
	echo json_encode($result);
} else {
	http_response_code(404);
	echo json_encode(['error' => 'Auditoría no encontrada']);
}
