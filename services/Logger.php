<?php

namespace App\Services;

class Logger
{
    private static string $logDir = __DIR__ . '/../logs';

    private static function ensureLogDirExists(): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }
    }

    private static function writeLog(string $level, string $message): void
    {
        self::ensureLogDirExists();

        $date = date('Y-m-d');
        $file = self::$logDir . "/$date.log";
        $time = date('H:i:s');

        $entry = "[$time][$level] $message" . PHP_EOL;
        file_put_contents($file, $entry, FILE_APPEND);
    }

    public static function info(string $message): void
    {
        self::writeLog('INFO', $message);
    }

    public static function warning(string $message): void
    {
        self::writeLog('WARNING', $message);
    }

    public static function error(string $message): void
    {
        self::writeLog('ERROR', $message);
    }

    public static function critical(string $message): void
    {
        self::writeLog('CRITICAL', $message);
    }

    public static function exception(\Throwable $e): void
    {
        $msg = sprintf(
            "Excepción: %s en %s:%d\nStack trace:\n%s\n",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
        self::writeLog('EXCEPTION', $msg);
    }

    public static function log($message, $context = [])
    {
        self::ensureLogDirExists();

        $file = self::$logDir . '/app.log';

        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $line = "[$date] $message $contextStr" . PHP_EOL;

        file_put_contents($file, $line, FILE_APPEND);
    }

    public static function request(string $extra = ''): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $route = $_SERVER['REQUEST_URI'] ?? 'unknown';

        $msg = "[$method] $route | IP: $ip";
        if ($extra) $msg .= " | $extra";

        self::info($msg);
    }
}
