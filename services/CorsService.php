<?php

namespace App\Services;

class CorsService
{
    public static function handle(): void
    {
        $origin = $_ENV['FRONTEND_ORIGIN'] ?? '*';

        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,PATCH , OPTIONS");
        header("Content-Type: application/json; charset=UTF-8");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
