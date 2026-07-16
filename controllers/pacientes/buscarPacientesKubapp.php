<?php

use App\Bootstrap\App;
use App\Models\Pacientes;
use App\Services\Logger;

try {
    $pdo = App::getPdo();

    $nombre = isset($params[0]) ? urldecode($params[0]) : null;
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre es requerido']);
        exit;
    }

    $PacientesModel = new Pacientes($pdo);
    $EpsModel = new \App\Models\Eps($pdo);
    
    $pacientes = $PacientesModel->buscarPorNombreOCedula($nombre);

    if (empty($pacientes)) {
        $externos = $PacientesModel->buscarPacientesKubapp($nombre);
        
        if (!empty($externos)) {
            foreach ($externos as $ext) {
                // Chequeo exacto por documento (número de Nit o Cédula)
                $existente = $PacientesModel->obtenerPorDocumento($ext['numeroNit']);
                
                if (!$existente) {
                    $eps_id = null;
                    if (!empty($ext['nombreEntidad'])) {
                        $eps = $EpsModel->obtenerPorNombre($ext['nombreEntidad']);
                        if (!$eps) {
                            $EpsModel->crear($ext['nombreEntidad']);
                            $eps = $EpsModel->obtenerPorNombre($ext['nombreEntidad']);
                        }
                        $eps_id = $eps['id'] ?? null;
                    }
                    
                    // Fecha de nacimiento se deja como null o vacía ya que la API no la retorna
                    $PacientesModel->crear(
                        $ext['numeroNit'],
                        $ext['nombreBen'],
                        null,
                        $eps_id
                    );
                }
            }
            $pacientes = $PacientesModel->buscarPorNombreOCedula($nombre);
        }
    }

    if (!empty($pacientes)) {
        echo json_encode(['success' => true, 'data' => $pacientes]);
    } else {
        echo json_encode(['success' => true, 'data' => [], 'message' => 'paciente no encontrado']);
    }
} catch (\Throwable $th) {

    Logger::exception($th);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    exit;
}
