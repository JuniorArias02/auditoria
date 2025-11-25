<?php

namespace App\Services;

class Logger
{
    public const APP = "app";
    public const SECURITY = "security";
    public const AUDITORIA = "auditoria";

    private static string $logDir = __DIR__ . '/../logs';

    private static function ensureLogDirExists(): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }
    }

    private static function writeLog(string $level, string $message, string $channel = self::APP): void
    {
        self::ensureLogDirExists();

        $date = date('Y-m-d');
        $file = self::$logDir . "/{$channel}_{$date}.log";

        $now = date('Y-m-d H:i:s');
        $entry = "[$now][$level] $message" . PHP_EOL;

        // Archivo físico
        file_put_contents($file, $entry, FILE_APPEND);

        // BD
        self::saveLogToDatabase($level, $message, $channel, $now);
    }

    private static function saveLogToDatabase(string $level, string $message, string $channel, string $timestamp): void
    {
        $host     = $_ENV['DB_HOST'];
        $database = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        try {
            $pdo = new \PDO(
                "mysql:host=$host;port=3306;dbname=$database;charset=utf8mb4",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );

            $stmt = $pdo->prepare("
                INSERT INTO system_logs (channel, level, message, created_at)
                VALUES (:channel, :level, :message, :created_at)
            ");

            $stmt->execute([
                ':channel'    => $channel,
                ':level'      => $level,
                ':message'    => $message,
                ':created_at' => $timestamp
            ]);

        } catch (\Throwable $e) {
            // Guardar error de BD en archivo
            $err = "[" . date("Y-m-d H:i:s") . "] DB_LOG_ERROR: " . $e->getMessage() . PHP_EOL;
            file_put_contents(self::$logDir . "/db_error.log", $err, FILE_APPEND);
        }
    }

    public static function info(string $message, string $channel = self::APP): void
    {
        self::writeLog('INFO', $message, $channel);
    }

    public static function warning(string $message, string $channel = self::APP): void
    {
        self::writeLog('WARNING', $message, $channel);
    }

    public static function error(string $message, string $channel = self::APP): void
    {
        self::writeLog('ERROR', $message, $channel);
    }

    public static function critical(string $message, string $channel = self::APP): void
    {
        self::writeLog('CRITICAL', $message, $channel);
    }

    public static function exception(\Throwable $e, string $channel = self::APP): void
    {
        $msg = sprintf(
            "Exception: %s in %s:%d\nStack trace:\n%s\n",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
        self::writeLog('EXCEPTION', $msg, $channel);
    }

    public static function request(string $extra = '', string $channel = self::APP): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $route = $_SERVER['REQUEST_URI'] ?? 'unknown';

        $msg = "[$method] $route | IP: $ip";
        if ($extra) $msg .= " | $extra";

        self::info($msg, $channel);
    }
}
