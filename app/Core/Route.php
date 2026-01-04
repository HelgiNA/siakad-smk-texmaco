<?php
namespace App\Core;

use App\Core\Middleware; // Import class Middleware yang baru dibuat

class Route
{
    private $routes = [];

    public function get($path, $callback, $middleware = [])
    {
        $this->routes["GET"][$path] = [
            "callback" => $callback,
            "middleware" => $middleware,
        ];
    }

    public function post($path, $callback, $middleware = [])
    {
        $this->routes["POST"][$path] = [
            "callback" => $callback,
            "middleware" => $middleware,
        ];
    }

    public function run()
    {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $method = $_SERVER["REQUEST_METHOD"];

        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];

            // 1. Eksekusi Middleware
            if (!empty($route["middleware"])) {
                $this->handleMiddleware($route["middleware"]);
            }

            // 2. Eksekusi Controller
            $callback = $route["callback"];
            if (is_array($callback)) {
                $controller = new ($callback[0])();
                $action = $callback[1];
                return $controller->$action();
            }
            return call_user_func($callback);
        }

        abort(404);
    }

    /**
     * Logika baru untuk menangani Array Middleware & Parameter
     */
    private function handleMiddleware($middlewares)
    {
        // Ubah jadi array jika inputnya string tunggal ("auth")
        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        foreach ($middlewares as $mw) {
            // Pecah string "role:Admin" menjadi ["role", "Admin"]
            $parts = explode(":", $mw);
            $key = $parts[0];
            $param = $parts[1] ?? null;

            // Panggil Middleware Resolver
            Middleware::resolve($key, $param);
        }
    }
}
