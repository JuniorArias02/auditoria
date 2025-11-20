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

    $notificaciones = $userSetting->obtenerNotificaciones($usuarioId);

    if ($notificaciones === null) {
        echo json_encode([
            'ok' => false,
            'msg' => 'Configuración no encontrada'
        ]);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'notificaciones' => $notificaciones
    ]);

} catch (\Throwable $th) {
    Logger::error('Error al obtener notificaciones: ' . $th->getMessage());

    echo json_encode([
        'ok' => false,
        'msg' => 'Error interno del servidor'
    ]);
}
