<?php

class Router {
    private static $routes = [];
    /**
     * Adds a route with an optional specific HTTP method
     *
     * @param string $pattern The URL pattern
     * @param callable $callback The callback function to execute
     * @param string|array|null $method The HTTP method(s) (GET, POST, etc.) or null for any method
     */
    public static function add($pattern, $callback, $method = null) {
        self::$routes[] = ['method' => $method, 'pattern' => $pattern, 'callback' => $callback];
    }

    /**
     * Adds a GET route
     *
     * @param string $pattern The URL pattern
     * @param callable $callback The callback function to execute
     */
    public static function get($pattern, $callback) {
        self::add($pattern, $callback, 'GET');
    }

    /**
     * Adds a POST route
     *
     * @param string $pattern The URL pattern
     * @param callable $callback The callback function to execute
     */
    public static function post($pattern, $callback) {
        self::add($pattern, $callback, 'POST');
    }

    /**
     * Routes the current request to the appropriate callback function
     */
    public static function route() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?'); // Remove query string from URI

        foreach (self::$routes as $route) {
            if ($route['method'] !== null && !in_array($requestMethod, (array) $route['method'])) {
                continue; // Skip if the request method does not match and method is not null
            }

            $pattern = preg_replace('/<([^>]+)>/', '([^/]+)', $route['pattern']);
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove the full URI match
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        // Handle not found scenario, you may want to customize this part.
        header("HTTP/1.0 404 Not Found");
        include $_SERVER['DOCUMENT_ROOT'] . "/.config/_404.php";
    }
}

?>
