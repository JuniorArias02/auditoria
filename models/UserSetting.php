<?php

namespace App\Models;

use PDO;
use Exception;

class UserSetting
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear configuración inicial
    public function crearInicial($userId)
    {
        $notificaciones = [
            'alertasAuditoria' => true,
            'estadisticasAuditorias' => true,
            'correoInicioSesion' => false,
            'recordatorios' => false,
            'puntajeAuditoria' => true
        ];

        $tema = 'light';

        $sql = "INSERT INTO user_settings (user_id, notificaciones, tema, creado_en, actualizado_en)
                VALUES (:user_id, :notificaciones, :tema, NOW(), NOW())";

        $stmt = $this->pdo->prepare($sql);

        $success = $stmt->execute([
            'user_id' => $userId,
            'notificaciones' => json_encode($notificaciones),
            'tema' => $tema
        ]);

        if (!$success) {
            throw new Exception("Error creando configuración inicial para el usuario $userId");
        }

        return $this->obtenerPorUsuario($userId);
    }


    // Obtener configuración
    public function obtenerPorUsuario($userId)
    {
        $sql = "SELECT * FROM user_settings WHERE user_id = :user_id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        $config = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$config) return null;

        $config['notificaciones'] = json_decode($config['notificaciones'], true);

        return $config;
    }


    // Crear configuración (solo si quisieras otro método)
    public function crear($userId, $notificaciones = [], $tema = "light")
    {
        $sql = "INSERT INTO user_settings (user_id, notificaciones, tema, creado_en, actualizado_en)
                VALUES (:user_id, :notificaciones, :tema, NOW(), NOW())";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'notificaciones' => json_encode($notificaciones),
            'tema' => $tema
        ]);
    }


    // Actualizar TODA la configuración
    public function actualizar($userId, $notificaciones, $tema)
    {
        $sql = "UPDATE user_settings
                SET notificaciones = :notificaciones, tema = :tema, actualizado_en = NOW()
                WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'notificaciones' => json_encode($notificaciones),
            'tema' => $tema
        ]);
    }


    // Actualizar solo notificaciones
    public function actualizarNotificaciones($userId, $notificacionesJson)
    {
        $sql = "UPDATE user_settings
            SET notificaciones = :notificaciones, actualizado_en = NOW()
            WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'notificaciones' => $notificacionesJson
        ]);
    }

    // Obtener solo las notificaciones
    public function obtenerNotificaciones($userId)
    {
        $sql = "SELECT notificaciones FROM user_settings WHERE user_id = :user_id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        return json_decode($data['notificaciones'], true);
    }




    // Actualizar solo tema
    public function actualizarTema($userId, $tema)
    {
        $sql = "UPDATE user_settings
                SET tema = :tema, actualizado_en = NOW()
                WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'tema' => $tema
        ]);
    }
}
