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
        // Registrando rotas usando o método `add`
        $this->add('GET',    '/users',         ['App\Controllers\UserController', 'index']);
        $this->add('GET',    '/users/{id}',    ['App\Controllers\UserController', 'show']);
        $this->add('GET',    '/users/tipo/{id}',    ['App\Controllers\UserController', 'showTipoUsuario']);
        $this->add('POST',   '/users',         ['App\Controllers\UserController', 'store']);
        $this->add('PUT',    '/users/{id}',    ['App\Controllers\UserController', 'update']);
        $this->add('DELETE', '/users/{id}',    ['App\Controllers\UserController', 'delete']);
        $this->add('POST', '/caixa/abrir', ['App\Controllers\CaixaController', 'abrirViaRequest']);

    }

    private function add(string $method, string $route, array $handler): void
    {
        $this->routes[$this->customStrToUpper($method)][$route] = $handler;
    }
    
    private function customStrToUpper(string $string): string
    {
        return mb_strtoupper($string, 'UTF-8');
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = $this->customStrToUpper($method);

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            // Converter rota com {param} para regex
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
