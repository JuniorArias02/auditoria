<?php


namespace App\Models;
use App\Database\Database;
use \PDO;

class Eps {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Crear EPS
    public function crear($nombre) {
        $sql = "INSERT INTO eps (nombre) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => $nombre]);
    }

    //Obtener todas las EPS
    public function obtenerTodos() {
        $sql = "SELECT * FROM eps ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Obtener EPS por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM eps WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Actualizar EPS
    public function actualizar($id, $nombre) {
        $sql = "UPDATE eps SET nombre = :nombre WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':id' => $id
        ]);
    }

    //Eliminar EPS
    public function eliminar($id) {
        $sql = "DELETE FROM eps WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
