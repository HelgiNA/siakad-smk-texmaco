<?php

class Route
{
    private $routes = [];

    public function get($path, $callback, $middleware = null)
    {
        $this->routes['GET'][$path] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function post($path, $callback, $middleware = null)
    {
        $this->routes['POST'][$path] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function run()
    {
        $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];

            // 1. Eksekusi Middleware jika ada
            if ($route['middleware']) {
                $this->executeMiddleware($route['middleware']);
            }

            // 2. Eksekusi Controller/Callback utama
            $callback = $route['callback'];
            if (is_array($callback)) {
                $controller = new $callback[0];
                $action     = $callback[1];
                return $controller->$action();
            }
            return call_user_func($callback);
        }

        http_response_code(404);
        echo "404 - Not Found";
    }

    private function executeMiddleware($middleware)
    {
        if (is_callable($middleware)) {
            call_user_func($middleware);
        }
    }
}