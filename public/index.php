<?php
// Core configuration and autoloading
require_once '../config/config.php';
require_once '../config/database.php';

// Simple autoloader
spl_autoload_register(function($class) {
    $prefixes = [
        '../app/Controllers/',
        '../app/Models/'
    ];
    
    foreach ($prefixes as $prefix) {
        $file = $prefix . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Routing logic
class Router {
    private $routes = [
        '' => 'HomeController@index',
        'home' => 'HomeController@index',
        'about' => 'AboutController@index',
        'contact' => 'ContactController@index'
    ];

    public function dispatch($uri) {
        // Remove query string and trim slashes
        $uri = strtok($uri, '?');
        
        // Extract the path after /php-mvc/public/
        $baseDir = '/php-mvc/public/';
        if (strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }
        
        $uri = trim($uri, '/');
        
        // Default to home if no route specified
        $uri = $uri ?: '';

        // Check if route exists
        if (!isset($this->routes[$uri])) {
            // 404 handling
            http_response_code(404);
            include __DIR__ . '/../app/Views/404.view.php';
            exit;
        }

        // Split controller and method
        list($controllerName, $method) = explode('@', $this->routes[$uri]);
        
        // Create controller instance and call method
        $controller = new $controllerName();
        $controller->$method();
    }
}

// Process request
$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
?>