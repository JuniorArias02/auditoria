<?php
require_once __DIR__ . '/../db/conexion.php';

class Criterio {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Crear un nuevo criterio
    public function crear($dimension_id, $descripcion, $orden) {
        $sql = "INSERT INTO criterios (dimension_id, descripcion, orden) VALUES (:dimension_id, :descripcion, :orden)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':dimension_id', $dimension_id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    // 🔹 Obtener todos los criterios
    public function obtenerTodos() {
        $sql = "SELECT c.*, d.nombre AS dimension_nombre 
                FROM criterios c 
                LEFT JOIN dimensiones d ON c.dimension_id = d.id
                ORDER BY c.orden ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener un criterio por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM criterios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener criterios por Dimension
    public function obtenerPorDimension($dimension_id) {
        $sql = "SELECT * FROM criterios WHERE dimension_id = :dimension_id ORDER BY orden ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':dimension_id', $dimension_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Actualizar un criterio
    public function actualizar($id, $dimension_id, $descripcion, $orden) {
        $sql = "UPDATE criterios 
                SET dimension_id = :dimension_id, descripcion = :descripcion, orden = :orden 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':dimension_id', $dimension_id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':orden', $orden, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // 🔹 Eliminar un criterio
    public function eliminar($id) {
        $sql = "DELETE FROM criterios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
