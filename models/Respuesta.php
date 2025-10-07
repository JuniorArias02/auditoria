<?php
require_once __DIR__ . '/../db/conexion.php';

class Respuesta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear nueva respuesta
    public function crear($auditoria_id, $criterio_id, $puntaje, $observaciones) {
        $sql = "INSERT INTO respuestas (auditoria_id, criterio_id, puntaje, observaciones) 
                VALUES (:auditoria_id, :criterio_id, :puntaje, :observaciones)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':auditoria_id' => $auditoria_id,
            ':criterio_id' => $criterio_id,
            ':puntaje' => $puntaje,
            ':observaciones' => $observaciones
        ]);
    }

    // Obtener todas las respuestas
    public function obtenerTodas() {
        $sql = "SELECT * FROM respuestas";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener respuesta por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM respuestas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar respuesta
    public function actualizar($id, $auditoria_id, $criterio_id, $puntaje, $observaciones) {
        $sql = "UPDATE respuestas 
                SET auditoria_id = :auditoria_id, 
                    criterio_id = :criterio_id, 
                    puntaje = :puntaje, 
                    observaciones = :observaciones 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':auditoria_id' => $auditoria_id,
            ':criterio_id' => $criterio_id,
            ':puntaje' => $puntaje,
            ':observaciones' => $observaciones
        ]);
    }

    // Eliminar respuesta
    public function eliminar($id) {
        $sql = "DELETE FROM respuestas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
