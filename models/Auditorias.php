<?php
require_once __DIR__ . '/../db/conexion.php';

class Auditoria
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear nueva auditoría
    public function crear($data)
    {

        $data['porcentaje_cumplimiento'] = round($data['porcentaje_cumplimiento'], 2);

        $sql = "INSERT INTO auditorias 
        (creador_id, formulario_auditoria_id, fecha_auditoria, fecha_atencion, servicio_auditado, paciente_id, sede_id, profesional_id, puntaje_total, total_criterios, porcentaje_cumplimiento)
        VALUES (:creador_id,:formulario_auditoria_id, :fecha_auditoria, :fecha_atencion, :servicio_auditado, :paciente_id, :sede_id, :profesional_id, :puntaje_total, :total_criterios, :porcentaje_cumplimiento)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'creador_id'              => $data['creador_id'],
            'formulario_auditoria_id' => $data['formulario_auditoria_id'],
            'fecha_auditoria'         => $data['fecha_auditoria'],
            'fecha_atencion'          => $data['fecha_atencion'],
            'servicio_auditado'       => $data['servicio_auditado'],
            'paciente_id'             => $data['paciente_id'],
            'sede_id'                 => $data['sede_id'],
            'profesional_id'          => $data['profesional_id'],
            'puntaje_total'           => $data['puntaje_total'],
            'total_criterios'         => $data['total_criterios'],
            'porcentaje_cumplimiento' => $data['porcentaje_cumplimiento']
        ]);

        return $this->pdo->lastInsertId(); 
    }



    // Obtener todas las auditorías
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM auditorias";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener auditoría por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM auditorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar auditoría
    public function actualizar($id, $data)
    {
        $sql = "UPDATE auditorias 
                SET fecha_auditoria = :fecha_auditoria,
                    fecha_atencion = :fecha_atencion,
                    servicio_auditado = :servicio_auditado,
                    paciente_id = :paciente_id,
                    sede_id = :sede_id,
                    profesional_id = :profesional_id,
                    puntaje_total = :puntaje_total
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'fecha_auditoria'  => $data['fecha_auditoria'],
            'fecha_atencion'   => $data['fecha_atencion'],
            'servicio_auditado' => $data['servicio_auditado'],
            'paciente_id'      => $data['paciente_id'],
            'sede_id'          => $data['sede_id'],
            'profesional_id'   => $data['profesional_id'],
            'puntaje_total'    => $data['puntaje_total'],
            'id'               => $id
        ]);
    }

    // Eliminar auditoría
    public function eliminar($id)
    {
        $sql = "DELETE FROM auditorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }


    public function mostrarInformeAuditorias()
    {
        $sql = "
        SELECT
            COUNT(*) AS total_auditorias,
            SUM(CASE WHEN puntaje_total >= 95 THEN 1 ELSE 0 END) AS mayores_95,
            SUM(CASE WHEN puntaje_total BETWEEN 85 AND 94 THEN 1 ELSE 0 END) AS entre_85_94,
            SUM(CASE WHEN puntaje_total < 85 THEN 1 ELSE 0 END) AS menores_85
        FROM auditorias
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function auditoriaRecientes()
    {
        $sql = "SELECT 
                a.id,
                p.nombre, 
                p.cargo, 
                a.fecha_auditoria, 
                a.porcentaje_cumplimiento
            FROM auditorias AS a
            LEFT JOIN profesionales p ON a.profesional_id = p.id
            ORDER BY a.fecha_auditoria DESC
            LIMIT 5";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resumenHoy($fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        $sql = "SELECT 
                COUNT(*) AS auditoriasHoy,
                AVG(porcentaje_cumplimiento) AS cumplimiento,
                AVG(puntaje_total) AS puntajeMaximo
            FROM auditorias
            WHERE fecha_auditoria = :fecha";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['fecha' => $fecha]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function metricasCalidadAuditoria()
    {
        $sql = "SELECT
            SUM(CASE WHEN porcentaje_cumplimiento < 85 THEN 1 ELSE 0 END) AS inaceptables,
            SUM(CASE WHEN porcentaje_cumplimiento BETWEEN 85 AND 94.99 THEN 1 ELSE 0 END) AS aceptables,
            SUM(CASE WHEN porcentaje_cumplimiento >= 95 THEN 1 ELSE 0 END) AS satisfactorias,
            ROUND(AVG(porcentaje_cumplimiento), 2) AS promedio_cumplimiento
            FROM auditorias";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function listarAuditorias()
    {
        $sql = "SELECT
            a.id,
            a.fecha_auditoria,
            a.puntaje_total,
            a.total_criterios,
            a.porcentaje_cumplimiento,
            pc.nombre_completo AS pacienteNombre,
            pc.documento AS pacienteDocumento,
            us.nombre_completo AS auditor,
            ser.nombre AS servicioAuditar
            FROM auditorias as a
            LEFT JOIN pacientes AS pc ON a.paciente_id = pc.id
            LEFT JOIN usuarios AS us ON us.id = a.creador_id
            LEFT JOIN servicio_auditar AS ser ON ser.id = a.servicio_auditado
        ORDER BY a.fecha_auditoria DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarAuditoriasFiltro($busqueda = null, $clasificacion = null, $fecha_inicio = null, $fecha_fin = null)
    {
        $sql = "SELECT
                a.id,
                a.fecha_auditoria,
                a.puntaje_total,
                a.total_criterios,
                a.porcentaje_cumplimiento,
                pc.nombre_completo AS pacienteNombre,
                pc.documento AS pacienteDocumento,
                us.nombre_completo AS auditor,
                ser.nombre AS servicioAuditar
            FROM auditorias AS a
            LEFT JOIN pacientes AS pc ON a.paciente_id = pc.id
            LEFT JOIN usuarios AS us ON us.id = a.creador_id
            LEFT JOIN servicio_auditar AS ser ON ser.id = a.servicio_auditado
            WHERE 1=1";

        // 🔍 Filtro por búsqueda (paciente o auditor)
        if (!empty($busqueda)) {
            $sql .= " AND (pc.nombre_completo LIKE :busqueda OR us.nombre_completo LIKE :busqueda)";
        }

        // 📊 Filtro por clasificación
        if (!empty($clasificacion)) {
            if ($clasificacion === 'inaceptable') {
                $sql .= " AND a.porcentaje_cumplimiento < 85";
            } elseif ($clasificacion === 'aceptable') {
                $sql .= " AND a.porcentaje_cumplimiento BETWEEN 85 AND 94.99";
            } elseif ($clasificacion === 'satisfactoria') {
                $sql .= " AND a.porcentaje_cumplimiento >= 95";
            }
        }

        // 📅 Filtro por fechas
        if (!empty($fecha_inicio)) {
            $sql .= " AND a.fecha_auditoria >= :fecha_inicio";
        }
        if (!empty($fecha_fin)) {
            $sql .= " AND a.fecha_auditoria <= :fecha_fin";
        }

        $sql .= " ORDER BY a.fecha_auditoria DESC";

        $stmt = $this->pdo->prepare($sql);

        // 🔗 Bind params opcionales
        if (!empty($busqueda)) {
            $like = "%{$busqueda}%";
            $stmt->bindParam(':busqueda', $like, PDO::PARAM_STR);
        }
        if (!empty($fecha_inicio)) {
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        }
        if (!empty($fecha_fin)) {
            $stmt->bindParam(':fecha_fin', $fecha_fin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function detalleAuditoria($id)
    {
        $sql = "SELECT
        a.fecha_auditoria AS fechaAuditoria,
        a.puntaje_total AS puntosObtenido,
        a.total_criterios AS puntosTotal,
        a.porcentaje_cumplimiento AS porcentaje,
        us.nombre_completo AS nombreAuditor,
        pc.nombre_completo AS nombrePaciente,
        pc.documento AS pacienteDocumento,
        pro.nombre AS nombreProfesional,
        ser.nombre AS servicioNombre,
        CONCAT(sd.nombre, ' - ', sd.tipo_modalidad) AS sedeCompleta
    FROM auditorias AS a
    LEFT JOIN usuarios AS us ON us.id = a.creador_id
    LEFT JOIN pacientes AS pc ON pc.id = a.paciente_id
    LEFT JOIN profesionales AS pro ON pro.id = a.profesional_id
    LEFT JOIN servicio_auditar AS ser ON ser.id = a.servicio_auditado
    LEFT JOIN sedes AS sd ON sd.id = a.sede_id
    WHERE a.id = :id
    LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function detalleAuditoriaEvaluacion($id)
    {
        $sql = "SELECT 
                r.puntaje,
                r.observaciones,
                c.descripcion AS criterioDescripcion,
                d.nombre AS dimensionNombre
            FROM respuestas AS r
            LEFT JOIN criterios AS c ON c.id = r.criterio_id
            LEFT JOIN dimensiones AS d ON d.id = c.dimension_id
            WHERE r.auditoria_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
