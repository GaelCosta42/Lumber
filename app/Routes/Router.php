<?php

namespace App\Routes;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $this->routes = [
            'GET' => [
                '/users' => ['App\Controllers\UserController', 'index'],
                '/users/{id}' => ['App\Controllers\UserController', 'show'],
            ],
            'POST' => [
                '/users' => ['App\Controllers\UserController', 'store'],
            ],
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$class, $methodName] = $handler;

                if (class_exists($class) && method_exists($class, $methodName)) {
                    $controller = new $class;
                    call_user_func_array([$controller, $methodName], $matches);
                    return;
                }

                http_response_code(500);
                echo json_encode(['error' => 'Handler não encontrado']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
    }
}
