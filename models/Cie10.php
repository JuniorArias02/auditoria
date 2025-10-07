<?php
require_once __DIR__ . '/../db/conexion.php';

class Cie10 {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear nuevo registro
    public function crear($codigo, $descripcion) {
        $sql = "INSERT INTO cie10 (codigo, descripcion) VALUES (:codigo, :descripcion)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    // Obtener todos los registros
    public function obtenerTodos() {
        $sql = "SELECT * FROM cie10 ORDER BY codigo ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM cie10 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar por código
    public function buscarPorCodigo($codigo) {
        $sql = "SELECT * FROM cie10 WHERE codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar
    public function actualizar($id, $codigo, $descripcion) {
        $sql = "UPDATE cie10 SET codigo = :codigo, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar
    public function eliminar($id) {
        $sql = "DELETE FROM cie10 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
