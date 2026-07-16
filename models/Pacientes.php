<?php

namespace App\Models;
use App\Database\Database;
use \PDO;


class Pacientes
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	// Listar todos los pacientes
	public function listar($query = '')
	{
		$isSearching = !empty($query);
		$limit = $isSearching ? 15 : 30;

		$sql = "SELECT p.*, e.nombre AS eps_nombre
            FROM pacientes p
            LEFT JOIN eps e ON p.eps_id = e.id
            WHERE (:query = '' OR p.nombre_completo LIKE :query OR p.documento LIKE :query)
            ORDER BY p.id DESC
            LIMIT :limit";

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
		$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
		$stmt->execute();
		$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$countSql = "SELECT COUNT(*) FROM pacientes 
                 WHERE (:query = '' OR nombre_completo LIKE :query OR documento LIKE :query)";
		$countStmt = $this->pdo->prepare($countSql);
		$countStmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
		$countStmt->execute();
		$total = $countStmt->fetchColumn();

		return [
			'data' => $pacientes,
			'total' => (int)$total,
		];
	}


	public function buscar($query)
	{
		$sql = "SELECT p.*, e.nombre AS eps_nombre
            FROM pacientes p
            LEFT JOIN eps e ON p.eps_id = e.id
            WHERE p.nombre_completo LIKE :query
               OR p.documento LIKE :query
            ORDER BY p.id DESC
            LIMIT 15";

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
		$stmt->execute();

		$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$countSql = "SELECT COUNT(*) FROM pacientes
                 WHERE nombre_completo LIKE :query
                    OR documento LIKE :query";
		$countStmt = $this->pdo->prepare($countSql);
		$countStmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
		$countStmt->execute();
		$total = $countStmt->fetchColumn();

		return [
			'data' => $pacientes,
			'total' => (int)$total,
		];
	}


	// Buscar paciente por ID
	public function obtener($id)
	{
		$stmt = $this->pdo->prepare("SELECT p.*, e.nombre AS eps_nombre FROM pacientes p LEFT JOIN eps e ON p.eps_id = e.id WHERE p.id = ?");
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Buscar paciente por documento exacto
	public function obtenerPorDocumento($documento)
	{
		$stmt = $this->pdo->prepare("SELECT p.*, e.nombre AS eps_nombre FROM pacientes p LEFT JOIN eps e ON p.eps_id = e.id WHERE p.documento = ?");
		$stmt->execute([$documento]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Buscar paciente por documento o nombre
	public function buscarPorNombreOCedula($texto)
	{
		$texto = "%$texto%";
		$sql = "
        SELECT p.*, e.nombre AS eps_nombre
        FROM pacientes p
        LEFT JOIN eps e ON p.eps_id = e.id
        WHERE p.nombre_completo LIKE :texto OR p.documento LIKE :texto
        LIMIT 50
    ";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	// Crear nuevo paciente
	public function crear($documento, $nombre_completo, $fecha_nacimiento, $eps_id)
	{
		$stmt = $this->pdo->prepare("INSERT INTO pacientes (documento, nombre_completo, fecha_nacimiento, eps_id) VALUES (?, ?, ?, ?)");
		return $stmt->execute([$documento, $nombre_completo, $fecha_nacimiento, $eps_id]);
	}

	// Actualizar paciente
	public function actualizar($id, $documento, $nombre_completo, $fecha_nacimiento, $eps_id)
	{
		$stmt = $this->pdo->prepare("UPDATE pacientes SET documento = ?, nombre_completo = ?, fecha_nacimiento = ?, eps_id = ? WHERE id = ?");
		return $stmt->execute([$documento, $nombre_completo, $fecha_nacimiento, $eps_id, $id]);
	}

	// Eliminar paciente
	public function eliminar($id)
	{
		$stmt = $this->pdo->prepare("DELETE FROM pacientes WHERE id = ?");
		return $stmt->execute([$id]);
	}

	public function buscarPacientesKubapp($texto)
	{
		$baseUrl = $_ENV['API_KUBAPP_TERCEROS'] ?? 'http://190.145.135.122:8090/api';
        $url = $baseUrl . "/beneficiarios/buscar-nombre?nombre=" . rawurlencode(urldecode($texto));

        // Debug: Log de la URL que se va a llamar
        \App\Services\Logger::info("Iniciando búsqueda externa Kubapp URL: $url");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
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

        if (empty($data)) {
            \App\Services\Logger::info("Búsqueda Kubapp finalizada: No se encontró contenido");
        }

        if (isset($data['content']) && is_array($data['content'])) {
            return $data['content'];
        }

        return $data ?? [];
	}
}

