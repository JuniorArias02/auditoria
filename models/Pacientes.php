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
}
