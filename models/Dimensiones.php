<?php


namespace App\Models;
use PDO;

require_once __DIR__ . '/../db/conexion.php';

class Dimensiones
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear nueva dimensión
    public function crear($nombre, $orden, $porcentaje)
    {
        $sql = "INSERT INTO dimensiones (nombre, orden, porcentaje) VALUES (:nombre, :orden , :porcentaje)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':orden', $orden);
        $stmt->bindParam(':porcentaje', $porcentaje);
        return $stmt->execute();
    }

    // Obtener todas las dimensiones
    public function listar()
    {
        $sql = "SELECT * FROM dimensiones ORDER BY orden ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener dimensión por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM dimensiones WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar una dimensión
    public function actualizar($id, $nombre, $orden)
    {
        $sql = "UPDATE dimensiones SET nombre = :nombre, orden = :orden WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':orden', $orden);
        return $stmt->execute();
    }

    // Eliminar una dimensión
    public function eliminar($id)
    {
        $sql = "DELETE FROM dimensiones WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
