<?php
namespace App\Bootstrap;

use Dotenv\Dotenv;
use App\Database\Database;
use App\Services\CorsService;
use App\Services\Logger;

class App
{
    private static ?\PDO $pdo = null;

    public static function init(): void
    {
        // Cargar .env
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        // CORS
        CorsService::handle();


        Logger::request();

        set_error_handler(function ($level, $message, $file, $line) {
            Logger::error("Error [$level]: $message en $file:$line");
        });

        set_exception_handler(function ($exception) {
            Logger::exception($exception);
        });

        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null) {
                Logger::critical("Fatal error: {$error['message']} en {$error['file']}:{$error['line']}");
            }
        });

        self::$pdo = Database::getConnection();
    }

    public static function getPdo(): \PDO
    {
        return self::$pdo;
    }
}
