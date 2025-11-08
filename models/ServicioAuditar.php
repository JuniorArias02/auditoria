<?php

namespace App\Models;
use PDO;

require_once __DIR__ . '/../db/conexion.php';

class ServicioAuditar
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear un servicio
    public function crear($nombre, $descripcion)
    {
        $sql = "INSERT INTO servicio_auditar (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }

    // Leer todos
    public function listar()
    {
        $sql = "SELECT * FROM servicio_auditar ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Leer uno por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM servicio_auditar WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorNombre($nombre)
    {
        $sql = "SELECT * FROM servicio_auditar WHERE nombre LIKE :nombre";
        $stmt = $this->pdo->prepare($sql);
        $busqueda = "%$nombre%";
        $stmt->bindParam(':nombre', $busqueda, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }




    // Actualizar
    public function actualizar($id, $nombre, $descripcion)
    {
        $sql = "UPDATE servicio_auditar SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }

    // Eliminar
    public function eliminar($id)
    {
        $sql = "DELETE FROM servicio_auditar WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
