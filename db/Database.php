<?php
namespace App\Database;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(): \PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config.php';
            try {
                self::$pdo = new \PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                    $config['user'],
                    $config['pass']
                );
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo->exec("SET time_zone = '-05:00'");
            } catch (\PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
