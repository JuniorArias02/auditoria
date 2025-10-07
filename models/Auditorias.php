<?php
require_once __DIR__ . '/../db/conexion.php';

class Auditoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear nueva auditoría
    public function crear($data) {
        $sql = "INSERT INTO auditorias 
                (fecha_auditoria, fecha_atencion, servicio_auditado, paciente_id, sede_id, cie10_id, profesional_id, puntaje_total)
                VALUES (:fecha_auditoria, :fecha_atencion, :servicio_auditado, :paciente_id, :sede_id, :cie10_id, :profesional_id, :puntaje_total)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'fecha_auditoria'  => $data['fecha_auditoria'],
            'fecha_atencion'   => $data['fecha_atencion'],
            'servicio_auditado'=> $data['servicio_auditado'],
            'paciente_id'      => $data['paciente_id'],
            'sede_id'          => $data['sede_id'],
            'cie10_id'         => $data['cie10_id'],
            'profesional_id'   => $data['profesional_id'],
            'puntaje_total'    => $data['puntaje_total']
        ]);
    }

    // Obtener todas las auditorías
    public function obtenerTodos() {
        $sql = "SELECT * FROM auditorias";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener auditoría por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM auditorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar auditoría
    public function actualizar($id, $data) {
        $sql = "UPDATE auditorias 
                SET fecha_auditoria = :fecha_auditoria,
                    fecha_atencion = :fecha_atencion,
                    servicio_auditado = :servicio_auditado,
                    paciente_id = :paciente_id,
                    sede_id = :sede_id,
                    cie10_id = :cie10_id,
                    profesional_id = :profesional_id,
                    puntaje_total = :puntaje_total
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'fecha_auditoria'  => $data['fecha_auditoria'],
            'fecha_atencion'   => $data['fecha_atencion'],
            'servicio_auditado'=> $data['servicio_auditado'],
            'paciente_id'      => $data['paciente_id'],
            'sede_id'          => $data['sede_id'],
            'cie10_id'         => $data['cie10_id'],
            'profesional_id'   => $data['profesional_id'],
            'puntaje_total'    => $data['puntaje_total'],
            'id'               => $id
        ]);
    }

    // Eliminar auditoría
    public function eliminar($id) {
        $sql = "DELETE FROM auditorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
