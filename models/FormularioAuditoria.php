<?php
require_once __DIR__ . '/../db/conexion.php';

class FormularioAuditoria
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🟢 Crear nuevo formulario
    public function crear($nombre_formulario, $descripcion = null)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO formulario_auditoria (nombre_formulario, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre_formulario, $descripcion]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 🟡 Actualizar formulario
    public function actualizar($id, $nombre_formulario, $descripcion = null)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE formulario_auditoria SET nombre_formulario = ?, descripcion = ? WHERE id = ?");
            return $stmt->execute([$nombre_formulario, $descripcion, $id]);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 🔴 Eliminar formulario
    public function eliminar($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM formulario_auditoria WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 🧾 Listar todos los formularios
    public function listar()
    {
        try {
            $stmt = $this->pdo->query("SELECT id, nombre_formulario, descripcion FROM formulario_auditoria ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 🔍 Obtener un formulario por ID
    public function obtenerPorId($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id, nombre_formulario, descripcion FROM formulario_auditoria WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function crearNuevoFormulario($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 1️⃣ Crear formulario
            $stmt = $this->pdo->prepare("INSERT INTO formulario_auditoria (nombre_formulario, descripcion) VALUES (?, ?)");
            $stmt->execute([$data['nombre_formulario'], $data['descripcion']]);
            $formularioId = $this->pdo->lastInsertId();

            // 2️⃣ Crear dimensiones
            foreach ($data['dimensiones'] as $dim) {
                $stmtDim = $this->pdo->prepare("INSERT INTO dimensiones (nombre, orden, porcentaje) VALUES (?, ?, ?)");
                $stmtDim->execute([$dim['nombre'], 0, $dim['porcentaje']]); // orden = 0 por ahora
                $dimensionId = $this->pdo->lastInsertId();

                // 3️⃣ Relacionar dimensión con el formulario
                $stmtRel = $this->pdo->prepare("INSERT INTO formulario_dimensiones (formulario_auditoria_id, dimension_id) VALUES (?, ?)");
                $stmtRel->execute([$formularioId, $dimensionId]);

                // 4️⃣ Crear criterios de esa dimensión
                foreach ($dim['criterios'] as $crit) {
                    $stmtCrit = $this->pdo->prepare("INSERT INTO criterios (dimension_id, descripcion, orden) VALUES (?, ?, ?)");
                    $stmtCrit->execute([$dimensionId, $crit['descripcion'], 0]); // orden = 0 por ahora
                }
            }

            $this->pdo->commit();
            return ['success' => true, 'formulario_id' => $formularioId];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['error' => $e->getMessage()];
        }
    }


    public function obtenerFormularioCompleto($id)
    {
        try {
            // 1️⃣ Traer datos del formulario
            $stmt = $this->pdo->prepare("SELECT * FROM formulario_auditoria WHERE id = ?");
            $stmt->execute([$id]);
            $formulario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$formulario) {
                return ['error' => 'Formulario no encontrado'];
            }

            // 2️⃣ Traer las dimensiones relacionadas
            $stmt = $this->pdo->prepare("
                SELECT d.id, d.nombre, d.orden, d.porcentaje
                FROM dimensiones d
                INNER JOIN formulario_dimensiones fd ON fd.dimension_id = d.id
                WHERE fd.formulario_auditoria_id = ?
                ORDER BY d.orden ASC
            ");
            $stmt->execute([$id]);
            $dimensiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 3️⃣ Para cada dimensión, traer los criterios
            foreach ($dimensiones as &$dim) {
                $stmt = $this->pdo->prepare("
                    SELECT id, descripcion, orden
                    FROM criterios
                    WHERE dimension_id = ?
                    ORDER BY orden ASC
                ");
                $stmt->execute([$dim['id']]);
                $criterios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $dim['criterios'] = $criterios;
            }

            // 4️⃣ Armar estructura final
            $formulario['dimensiones'] = $dimensiones;

            return $formulario;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function actualizarFormularioCompleto($data)
    {
        try {
            $this->pdo->beginTransaction();

            // 🔹 1. Actualizar datos principales del formulario
            $stmt = $this->pdo->prepare("UPDATE formulario_auditoria 
            SET nombre_formulario = ?, descripcion = ?
            WHERE id = ?");
            $stmt->execute([
                $data['nombre_formulario'],
                $data['descripcion'],
                $data['id']
            ]);

            // 🔹 2. Recorremos dimensiones
            foreach ($data['dimensiones'] as $dim) {

                // Si existe ID => actualizar
                if (isset($dim['id'])) {
                    $stmt = $this->pdo->prepare("UPDATE dimensiones 
                    SET nombre = ?, orden = ?, porcentaje = ?
                    WHERE id = ?");
                    $stmt->execute([
                        $dim['nombre'],
                        $dim['orden'],
                        $dim['porcentaje'],
                        $dim['id']
                    ]);
                } else {
                    // Si no existe => insertar nueva
                    $stmt = $this->pdo->prepare("INSERT INTO dimensiones (nombre, orden, porcentaje) VALUES (?, ?, ?)");
                    $stmt->execute([$dim['nombre'], $dim['orden'], $dim['porcentaje']]);
                    $dim['id'] = $this->pdo->lastInsertId();

                    // Relacionarla al formulario
                    $stmt = $this->pdo->prepare("INSERT INTO formulario_dimensiones (formulario_auditoria_id, dimension_id) VALUES (?, ?)");
                    $stmt->execute([$data['id'], $dim['id']]);
                }

                // 🔹 3. Actualizar o insertar criterios
                foreach ($dim['criterios'] as $crit) {
                    if (isset($crit['id'])) {
                        $stmt = $this->pdo->prepare("UPDATE criterios SET descripcion = ?, orden = ? WHERE id = ?");
                        $stmt->execute([$crit['descripcion'], $crit['orden'], $crit['id']]);
                    } else {
                        $stmt = $this->pdo->prepare("INSERT INTO criterios (dimension_id, descripcion, orden) VALUES (?, ?, ?)");
                        $stmt->execute([$dim['id'], $crit['descripcion'], $crit['orden']]);
                    }
                }
            }

            $this->pdo->commit();
            return ["success" => true, "message" => "Formulario actualizado correctamente"];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
}
