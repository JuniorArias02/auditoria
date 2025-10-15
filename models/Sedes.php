<?php
require_once __DIR__ . '/../db/conexion.php';

class Sede
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Crear nueva sede
    public function crear($nombre, $tipo_modalidad)
    {
        $sql = "INSERT INTO sedes (nombre, tipo_modalidad) VALUES (:nombre, :tipo_modalidad)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $nombre,
            'tipo_modalidad' => $tipo_modalidad
        ]);
    }

    // 🔹 Obtener todas las sedes
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM sedes ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener sede por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM sedes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorNombre($nombre)
    {
        $sql = "SELECT * FROM sedes WHERE nombre LIKE :nombre";
        $stmt = $this->pdo->prepare($sql);
        $busqueda = "%$nombre%";
        $stmt->bindParam(':nombre', $busqueda, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    // 🔹 Actualizar sede
    public function actualizar($id, $nombre, $tipo_modalidad)
    {
        $sql = "UPDATE sedes SET nombre = :nombre, tipo_modalidad = :tipo_modalidad WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'tipo_modalidad' => $tipo_modalidad
        ]);
    }

    // 🔹 Eliminar sede
    public function eliminar($id)
    {
        $sql = "DELETE FROM sedes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
