<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass();
                $controller->$action();
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
