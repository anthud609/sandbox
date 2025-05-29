<?php

declare(strict_types=1);

namespace Omni\Core\Core\Router;

class Router
{
    /**
     * @var array<string, array<string, callable|array{class-string, string}>>
     */
    private array $routes = [];

    /**
     * @param string $path
     * @param callable|array{class-string, string} $handler
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * @param string $path
     * @param callable|array{class-string, string} $handler
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];

            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_array($handler) && count($handler) === 2 && is_string($handler[0]) && is_string($handler[1])) {
                [$controllerClass, $controllerMethod] = $handler;
                $controller = new $controllerClass();
                $controller->$controllerMethod();
            }
        } else {
            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}
