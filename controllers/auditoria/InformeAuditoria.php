<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Auditorias.php';


$auditoria = new Auditoria($pdo);
$result = $auditoria->mostrarInformeAuditorias();

if ($result) {
	echo json_encode($result);
} else {
	http_response_code(404);
	echo json_encode(['error' => 'Auditoría no encontrada']);
}
