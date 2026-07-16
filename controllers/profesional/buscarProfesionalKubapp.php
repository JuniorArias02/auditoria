<?php

use App\Bootstrap\App;
use App\Models\Profesional;
use App\Services\Logger;

try {

    $pdo = App::getPdo();

    $nombre = isset($params[0]) ? urldecode($params[0]) : null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $ProfesionalModel = new Profesional($pdo);
    $profesionales = $ProfesionalModel->buscarPorNombreOCedula($nombre);

    if (empty($profesionales)) {
        $externos = $ProfesionalModel->buscarProfesionalKubapp($nombre);
        
        if (!empty($externos)) {
            foreach ($externos as $ext) {
                // Chequeo exacto por cédula
                $existente = $ProfesionalModel->obtenerPorCedula($ext['codigoUsr']);
                
                if (!$existente) {
                    $ProfesionalModel->crear(
                        $ext['nombreUsr'],
                        $ext['codigoUsr'],
                        $ext['nombreEsp']
                    );
                }
            }
            $profesionales = $ProfesionalModel->buscarPorNombreOCedula($nombre);
        }
    }

    if (!empty($profesionales)) {
        echo json_encode(['success' => true, 'data' => $profesionales]);
    } else {
        echo json_encode(['success' => true, 'data' => [], 'message' => 'profesional no encontrado']);
    }
} catch (\Exception $th) {
    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
