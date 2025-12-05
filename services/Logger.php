<?php

namespace App\Services;

use App\Database\Database;

class Logger
{
    private static ?Logger $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    private static function getInstance(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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

        $now  = date('Y-m-d H:i:s');
        $entry = "[$now][$level] $message" . PHP_EOL;

        file_put_contents($file, $entry, FILE_APPEND);

        self::getInstance()->saveLogToDatabase($level, $message, $channel, $now);
    }

    private function saveLogToDatabase(string $level, string $message, string $channel, string $timestamp): void
    {
        try {
            $logModel = new \App\Models\SystemLog($this->pdo);
            $logModel->create($channel, $level, $message, $timestamp);
        } catch (\Throwable $e) {
            $err = "[" . date("Y-m-d H:i:s") . "] DB_LOG_ERROR: {$e->getMessage()}" . PHP_EOL;
            file_put_contents(self::$logDir . "/db_error.log", $err, FILE_APPEND);
        }
    }

    // ---------- API pública estática ----------
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
        $ip     = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $route  = $_SERVER['REQUEST_URI'] ?? 'unknown';

        $msg = "[$method] $route | IP: $ip";
        if ($extra) $msg .= " | $extra";

        self::info($msg, $channel);
    }
}
