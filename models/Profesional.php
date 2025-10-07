<?php
require_once __DIR__ . '/../db/conexion.php';

class Profesional {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener todos los profesionales
    public function obtenerTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM profesionales ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un profesional por ID
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM profesionales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo profesional
    public function crear($nombre, $cargo) {
        $stmt = $this->pdo->prepare("INSERT INTO profesionales (nombre, cargo) VALUES (?, ?)");
        return $stmt->execute([$nombre, $cargo]);
    }

    // Actualizar un profesional
    public function actualizar($id, $nombre, $cargo) {
        $stmt = $this->pdo->prepare("UPDATE profesionales SET nombre = ?, cargo = ? WHERE id = ?");
        return $stmt->execute([$nombre, $cargo, $id]);
    }

    // Eliminar un profesional
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM profesionales WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
