<?php
require_once __DIR__ . '/../db/conexion.php';

class Pacientes
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	// Listar todos los pacientes
	public function listar()
	{
		$stmt = $this->pdo->prepare("SELECT p.*, e.nombre AS eps_nombre FROM pacientes p LEFT JOIN eps e ON p.eps_id = e.id");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
