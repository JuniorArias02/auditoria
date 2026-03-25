<?php

namespace App\Models;

use App\Database\Database;
use \PDO;

class Profesional
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los profesionales
    public function obtenerTodos()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM profesionales ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un profesional por ID
    public function obtenerPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM profesionales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCedula($cedula)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM profesionales WHERE cedula = ?");
        $stmt->execute([$cedula]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorNombreOCedula($texto)
    {
        $texto = "%$texto%";
        $sql = "
        SELECT p.*
        FROM profesionales p
        WHERE p.nombre LIKE :texto OR p.cedula LIKE :texto
        LIMIT 50
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo profesional
    public function crear($nombre, $cedula, $cargo)
    {
        $stmt = $this->pdo->prepare("INSERT INTO profesionales (nombre,cedula,cargo) VALUES (?,?,?)");
        return $stmt->execute([$nombre, $cedula, $cargo]);
    }

    // Actualizar un profesional
    public function actualizar($id, $nombre, $cedula, $cargo)
    {
        $stmt = $this->pdo->prepare("UPDATE profesionales SET nombre = ?,cedula = ?, cargo = ? WHERE id = ?");
        return $stmt->execute([$nombre, $cedula, $cargo, $id]);
    }

    // Eliminar un profesional
    public function eliminar($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM profesionales WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function buscarProfesionalKubapp($texto)
    {
        $baseUrl = $_ENV['API_KUBAPP_TERCEROS'] ?? 'http://190.145.135.122:8090/api';
        $url = $baseUrl . "/usuarios/buscar?nombre=" . rawurlencode(urldecode($texto));

        // Debug: Log de la URL que se va a llamar
        \App\Services\Logger::info("Iniciando búsqueda externa Kubapp URL: $url");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AuditoriaApp/1.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            \App\Services\Logger::error("Error de CURL en Kubapp ($url): " . $curlError);
            return [];
        }

        \App\Services\Logger::info("Respuesta Kubapp recibida (HTTP $httpCode). Longitud: " . strlen($response));

        $data = json_decode($response, true);
        
        // Si la decodificación falla, podría ser por problemas de codificación (ISO-8859-1)
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            \App\Services\Logger::warning("JSON inválido detectado, intentando conversión de encoding...");
            $response = mb_convert_encoding($response, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            $data = json_decode($response, true);
        }

        if (empty($data['content'])) {
            \App\Services\Logger::info("Búsqueda Kubapp finalizada: No se encontró contenido en 'content'");
        }

        return $data['content'] ?? [];
    }
}