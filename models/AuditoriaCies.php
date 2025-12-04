<?php

namespace App\Models;
use App\Database\Database;
use \PDO;


class AuditoriaCies {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function crear($cie10_id, $auditorias_id) {
        $sql = "INSERT INTO auditoria_cies (cie10_id, auditorias_id) VALUES (:cie10_id, :auditorias_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'cie10_id' => $cie10_id,
            'auditorias_id' => $auditorias_id
        ]);
    }
    public function obtenerPorAuditoria($auditorias_id) {
        $sql = "SELECT c.id, c.codigo, c.descripcion
                FROM auditoria_cies ac
                JOIN cie10 c ON ac.cie10_id = c.id
                WHERE ac.auditorias_id = :auditorias_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['auditorias_id' => $auditorias_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function eliminarPorAuditoria($auditorias_id) {
        $sql = "DELETE FROM auditoria_cies WHERE auditorias_id = :auditorias_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['auditorias_id' => $auditorias_id]);
    }
}
