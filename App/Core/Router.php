<?php

class Router {

    private $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    public function get($path, $handler) {
        $this->routes["GET"][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes["POST"][$path] = $handler;
    }

    public function put($path, $handler) {
        $this->routes["PUT"][$path] = $handler;
    }

    public function delete($path, $handler) {
        $this->routes["DELETE"][$path] = $handler;
    }

    public function dispatch() {
        $method = $_SERVER["REQUEST_METHOD"];
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        // Remove /api base path
        $uri = str_replace("/api", "", $uri);

        foreach ($this->routes[$method] as $route => $handler) {

            $regex = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_-]+)', $route);

            if (preg_match("#^$regex$#", $uri, $matches)) {
                array_shift($matches);

                $controller = new $handler[0]();
                return call_user_func_array([$controller, $handler[1]], $matches);
            }
        }

        Response::error("Route not found: $uri", 404);
    }
}

