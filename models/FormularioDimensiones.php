<?php


namespace App\Models;
use App\Database\Database;
use \PDO;

use PDOException;


class FormularioDimensiones
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function crear($formulario_auditoria_id, $dimension_id)
	{
		try {
			$stmt = $this->pdo->prepare("INSERT INTO formulario_dimensiones (formulario_auditoria_id, dimension_id) VALUES (?, ?)");
			$stmt->execute([$formulario_auditoria_id, $dimension_id]);
			return $this->pdo->lastInsertId();
		} catch (PDOException $e) {
			return ['error' => $e->getMessage()];
		}
	}

	// 🔹 Nuevo método para traer las dimensiones con sus criterios
	public function listarPorFormulario($formulario_auditoria_id)
	{
		try {
			$sql = "
				SELECT 
					d.id AS dimension_id,
					d.nombre AS dimension_nombre,
					d.orden AS dimension_orden,
					d.porcentaje AS dimension_porcentaje,
					c.id AS criterio_id,
					c.descripcion AS criterio_descripcion,
					c.orden AS criterio_orden
				FROM formulario_dimensiones fd
				INNER JOIN dimensiones d ON fd.dimension_id = d.id
				LEFT JOIN criterios c ON d.id = c.dimension_id
				WHERE fd.formulario_auditoria_id = ?
				ORDER BY d.orden, c.orden
			";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([$formulario_auditoria_id]);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Agrupar por dimensión
			$dimensiones = [];
			foreach ($result as $row) {
				$id = $row['dimension_id'];

				if (!isset($dimensiones[$id])) {
					$dimensiones[$id] = [
						'id' => $row['dimension_id'],
						'nombre' => $row['dimension_nombre'],
						'orden' => $row['dimension_orden'],
						'porcentaje' => $row['dimension_porcentaje'],
						'criterios' => []
					];
				}

				if ($row['criterio_id']) {
					$dimensiones[$id]['criterios'][] = [
						'id' => $row['criterio_id'],
						'descripcion' => $row['criterio_descripcion'],
						'orden' => $row['criterio_orden']
					];
				}
			}

			return array_values($dimensiones);
		} catch (PDOException $e) {
			return ['error' => $e->getMessage()];
		}
	}
}
