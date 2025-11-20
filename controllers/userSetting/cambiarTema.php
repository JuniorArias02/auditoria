<?php

use App\Bootstrap\App;
use App\Middlewares\AuthMiddleware;
use App\Services\Logger;
use App\Models\UserSetting;

try {

    $userData = AuthMiddleware::check();
    $usuarioId = $userData['id'];

    $app = new App();
    $pdo = $app->getPdo();

    $userSetting = new UserSetting($pdo);

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['tema'])) {
        echo json_encode([
            'ok' => false,
            'msg' => 'Falta el campo: tema'
        ]);
        exit;
    }

    $tema = $data['tema'];

    if (!in_array($tema, ['light', 'dark'])) {
        echo json_encode([
            'ok' => false,
            'msg' => 'El tema debe ser light o dark'
        ]);
        exit;
    }

    $resultado = $userSetting->actualizarTema($usuarioId, $tema);

    echo json_encode([
        'ok' => $resultado,
        'msg' => $resultado ? 'Tema actualizado correctamente' : 'No se pudo actualizar el tema',
        'tema' => $tema
    ]);

} catch (\Throwable $th) {

    Logger::error('Error al actualizar tema: ' . $th->getMessage());

    echo json_encode([
        'ok' => false,
        'msg' => 'Error interno del servidor'
    ]);
}
