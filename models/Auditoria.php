<?php

namespace App\Models;

use App\Database\Database;
use \PDO;

use Exception;



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

        // Convertir la fecha local a rango UTC
        // Inicio del día en UTC (00:00:00 local)
        $inicioLocal = new \DateTime($fecha . ' 00:00:00');
        $inicioUtc = clone $inicioLocal;
        $inicioUtc->setTimezone(new \DateTimeZone('UTC'));

        // Fin del día en UTC (23:59:59 local)
        $finLocal = new \DateTime($fecha . ' 23:59:59');
        $finUtc = clone $finLocal;
        $finUtc->setTimezone(new \DateTimeZone('UTC'));

        $sql = "SELECT 
            COUNT(*) AS auditoriasHoy,
            AVG(porcentaje_cumplimiento) AS cumplimiento,
            AVG(puntaje_total) AS puntajeMaximo
        FROM auditorias
        WHERE fecha_auditoria >= :inicio 
        AND fecha_auditoria <= :fin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'inicio' => $inicioUtc->format('Y-m-d H:i:s'),
            'fin' => $finUtc->format('Y-m-d H:i:s')
        ]);

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
                d.nombre AS dimensionNombre,
                r.observaciones AS respuestaObservaciones
            FROM respuestas AS r
            LEFT JOIN criterios AS c ON c.id = r.criterio_id
            LEFT JOIN dimensiones AS d ON d.id = c.dimension_id
            WHERE r.auditoria_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtener resumen de auditorías.
     *
     * Reglas:
     * - $dias === 0 => rango = mes actual (desde primer día del mes hasta hoy). comparativa = mes calendario anterior.
     * - $dias === 30 => rango = mes calendario anterior completo. comparativa = mes anterior a ese.
     * - $dias > 0 => rango = últimos $dias días (incluye hoy). comparativa = $dias días anteriores a ese rango.
     *
     * Retorna: auditorias, cumplimiento, profesionales y auditorias_mes (total mes calendario actual)
     */
    public function obtenerResumenAuditorias($fechaInicio, $fechaFin)
    {
        // Variación %
        $variacion = function ($actual, $anterior) {
            return $anterior > 0 ? round((($actual - $anterior) / $anterior) * 100, 2) : 0;
        };

        // ============================
        // 📊 AUDITORÍAS (actual)
        // ============================
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM auditorias 
        WHERE fecha_auditoria BETWEEN ? AND ?
    ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        $auditorias_actual = (int)$stmt->fetchColumn();

        // ============================
        // 📉 AUDITORÍAS (comparativa)
        // ============================
        // Resta el mismo rango hacia atrás
        $diasRango = (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400;

        $comparativa_fin = date('Y-m-d', strtotime("$fechaInicio -1 day"));
        $comparativa_inicio = date('Y-m-d', strtotime("$comparativa_fin -$diasRango days"));

        $stmt->execute([$comparativa_inicio, $comparativa_fin]);
        $auditorias_anterior = (int)$stmt->fetchColumn();

        // ============================
        // 🎯 CUMPLIMIENTO PROMEDIO
        // ============================
        $stmt = $this->pdo->prepare("
        SELECT AVG(porcentaje_cumplimiento) 
        FROM auditorias 
        WHERE fecha_auditoria BETWEEN ? AND ?
    ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        $cumplimiento_actual = round((float)$stmt->fetchColumn(), 2);

        $stmt->execute([$comparativa_inicio, $comparativa_fin]);
        $cumplimiento_anterior = round((float)$stmt->fetchColumn(), 2);

        // ============================
        // 👥 PROFESIONALES ÚNICOS
        // ============================
        $stmt = $this->pdo->prepare("
        SELECT COUNT(DISTINCT profesional_id) 
        FROM auditorias 
        WHERE fecha_auditoria BETWEEN ? AND ?
    ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        $profesionales_actual = (int)$stmt->fetchColumn();

        $stmt->execute([$comparativa_inicio, $comparativa_fin]);
        $profesionales_anterior = (int)$stmt->fetchColumn();

        // ============================
        // 🗓️ AUDITORÍAS DEL MES ACTUAL
        // ============================
        $mes_inicio = date('Y-m-01');
        $mes_fin = date('Y-m-t');

        $stmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM auditorias 
        WHERE fecha_auditoria BETWEEN ? AND ?
    ");
        $stmt->execute([$mes_inicio, $mes_fin]);
        $auditorias_mes_actual = (int)$stmt->fetchColumn();

        // Mes anterior
        $mes_anterior_inicio = date('Y-m-01', strtotime('-1 month'));
        $mes_anterior_fin = date('Y-m-t', strtotime('-1 month'));

        $stmt->execute([$mes_anterior_inicio, $mes_anterior_fin]);
        $auditorias_mes_anterior = (int)$stmt->fetchColumn();

        // ============================
        // 🔢 TOTAL
        // ============================
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM auditorias");
        $total_auditorias = (int)$stmt->fetchColumn();

        return [
            'rango' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin,
                'comparativa_inicio' => $comparativa_inicio,
                'comparativa_fin' => $comparativa_fin
            ],
            'auditorias' => [
                'actual' => $auditorias_actual,
                'anterior' => $auditorias_anterior,
                'variacion' => $variacion($auditorias_actual, $auditorias_anterior)
            ],
            'cumplimiento_promedio' => [
                'actual' => $cumplimiento_actual,
                'anterior' => $cumplimiento_anterior,
                'variacion' => $variacion($cumplimiento_actual, $cumplimiento_anterior)
            ],
            'profesionales' => [
                'actual' => $profesionales_actual,
                'anterior' => $profesionales_anterior,
                'variacion' => $variacion($profesionales_actual, $profesionales_anterior)
            ],
            'auditorias_mes' => [
                'actual' => $auditorias_mes_actual,
                'anterior' => $auditorias_mes_anterior,
                'variacion' => $variacion($auditorias_mes_actual, $auditorias_mes_anterior)
            ],
            'ultimo_numero_auditoria' => $total_auditorias
        ];
    }


    public function TendenciaCumplimento($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT 
            DATE_FORMAT(fecha_auditoria, '%Y-%m') AS mes,
            ROUND(AVG(porcentaje_cumplimiento), 2) AS promedio_cumplimiento
        FROM auditorias
        WHERE fecha_auditoria BETWEEN ? AND ?
        AND porcentaje_cumplimiento IS NOT NULL
        GROUP BY DATE_FORMAT(fecha_auditoria, '%Y-%m')
        ORDER BY mes ASC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function CumplimientoPorDimension($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT 
            d.nombre AS dimension,
            ROUND(AVG(r.puntaje) * 100, 2) AS promedio_cumplimiento
        FROM respuestas r
        INNER JOIN criterios c ON r.criterio_id = c.id
        INNER JOIN dimensiones d ON c.dimension_id = d.id
        INNER JOIN auditorias a ON r.auditoria_id = a.id
        WHERE a.fecha_auditoria BETWEEN ? AND ?
        GROUP BY d.id, d.nombre
        ORDER BY promedio_cumplimiento DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function CumplimientoPorServicio($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT 
            s.nombre AS servicio,
            ROUND(AVG(a.porcentaje_cumplimiento), 2) AS promedio_cumplimiento
        FROM auditorias a
        INNER JOIN servicio_auditar s ON a.servicio_auditado = s.id
        WHERE a.porcentaje_cumplimiento IS NOT NULL
        AND a.fecha_auditoria BETWEEN ? AND ?
        GROUP BY s.id, s.nombre
        ORDER BY promedio_cumplimiento DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function TopProfesionales($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT 
            p.id,
            p.nombre,
            p.cargo,
            COUNT(a.id) AS total_auditorias,
            ROUND(AVG(a.porcentaje_cumplimiento), 2) AS promedio_cumplimiento,
            RANK() OVER (ORDER BY AVG(a.porcentaje_cumplimiento) DESC) AS puesto
        FROM auditorias a
        INNER JOIN profesionales p ON a.profesional_id = p.id
        WHERE a.porcentaje_cumplimiento IS NOT NULL
        AND a.fecha_auditoria BETWEEN ? AND ?
        GROUP BY p.id, p.nombre, p.cargo
        ORDER BY promedio_cumplimiento DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerReportesCompleto($fechaInicio, $fechaFin)
    {
        try {
            return [
                'tendencia'         => $this->TendenciaCumplimento($fechaInicio, $fechaFin),
                'por_dimension'     => $this->CumplimientoPorDimension($fechaInicio, $fechaFin),
                'por_servicio'      => $this->CumplimientoPorServicio($fechaInicio, $fechaFin),
                'top_profesionales' => $this->TopProfesionales($fechaInicio, $fechaFin)
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'mensaje' => 'Error al cargar el dashboard: ' . $e->getMessage()
            ];
        }
    }
}
