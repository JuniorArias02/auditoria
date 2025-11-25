<?php
use App\Bootstrap\App;
use App\Models\Auditoria;
use App\Services\Logger;


try {
    
    $pdo = App::getPdo();

    $auditoria = new Auditoria($pdo);
    $resultado = $auditoria->mostrarInformeAuditorias();

    if (!$resultado) {
        throw new \Exception('Auditoría no encontrada', 404);
    }

    echo json_encode([
        'success' => true,
        'data' => $resultado
    ]);

} catch (\Exception $e) {
    Logger::exception($e);
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
