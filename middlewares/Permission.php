<?php
namespace App\Middlewares;

use App\Bootstrap\App;
use App\Services\Logger;
use PDO;
use Exception;

class Permission
{
    private PDO $pdo;
    private array $user;

    public function __construct(array $user)
    {
        $this->pdo = App::getPdo();
        $this->user = $user;
    }

    public function require(string $permiso): void
    {
        try {
            $sql = "SELECT p.nombre
                    FROM permisos p
                    JOIN rol_permiso rp ON rp.permiso_id = p.id
                    JOIN usuarios u ON u.rol_id = rp.rol_id
                    WHERE u.id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $this->user['id']]);
            $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array($permiso, $permisos)) {
                http_response_code(403);
                echo json_encode(['error' => 'No tienes permiso para esta acción.']);
                exit;
            }
        } catch (Exception $e) {
            Logger::exception($e);
            http_response_code(500);
            echo json_encode(['error' => 'Error al verificar permisos']);
            exit;
        }
    }
}
