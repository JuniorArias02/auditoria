<?php
namespace App\Utils;

use App\Services\Logger;

class Router
{
    private static array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => []
    ];

    public static function get(string $pattern, string $file) {
        self::$routes['GET'][$pattern] = $file;
    }

    public static function post(string $pattern, string $file) {
        self::$routes['POST'][$pattern] = $file;
    }

    public static function put(string $pattern, string $file) {
        self::$routes['PUT'][$pattern] = $file;
    }

    public static function patch(string $pattern, string $file) {
        self::$routes['PATCH'][$pattern] = $file;
    }

    public static function delete(string $pattern, string $file) {
        self::$routes['DELETE'][$pattern] = $file;
    }

    public static function dispatch() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];
        $found = false;

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $pattern => $file) {
                if (preg_match("#^$pattern$#", $uri, $matches)) {
                    $found = true;
                    $params = array_slice($matches, 1);

                    Logger::info("[$method] $uri");
                    require __DIR__ . '/../' . $file;
                    break;
                }
            }
        }

        if (!$found) {
            Logger::warning("Ruta no encontrada o método inválido: [$method] $uri");
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada', 'uri' => $uri]);
        }
    }
}
