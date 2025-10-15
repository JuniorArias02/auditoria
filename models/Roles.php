<?php
class Roles
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function listar()
	{
		$stmt = $this->pdo->query("SELECT * FROM roles ORDER BY id DESC");
		return ["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
	}

	public function crear($data)
	{
		if (empty($data['nombre'])) {
			return ["success" => false, "message" => "El nombre es obligatorio"];
		}

		$stmt = $this->pdo->prepare("INSERT INTO roles (nombre) VALUES (:nombre)");
		$stmt->execute([':nombre' => $data['nombre']]);
		return ["success" => true, "message" => "Rol creado correctamente"];
	}

	public function editar($data)
	{
		if (empty($data['id']) || empty($data['nombre'])) {
			return ["success" => false, "message" => "Datos incompletos"];
		}

		$stmt = $this->pdo->prepare("UPDATE roles SET nombre=:nombre WHERE id=:id");
		$stmt->execute([':nombre' => $data['nombre'], ':id' => $data['id']]);
		return ["success" => true, "message" => "Rol actualizado correctamente"];
	}

	public function eliminar($id)
	{
		if (!$id) {
			return ["success" => false, "message" => "ID requerido"];
		}

		$stmt = $this->pdo->prepare("DELETE FROM roles WHERE id=:id");
		$stmt->execute([':id' => $id]);
		return ["success" => true, "message" => "Rol eliminado correctamente"];
	}
}
