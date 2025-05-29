<?php

declare(strict_types=1);

namespace Omni\Core\Core\Router;

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];

            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_array($handler) && count($handler) === 2) {
                $controller = new $handler[0]();
                $method = $handler[1];
                $controller->$method();
            }
        } else {
            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}
