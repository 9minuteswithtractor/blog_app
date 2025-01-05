<?php

/**
 * A very basic Router.
 */
class Router
{
    protected $routes = [];

    /**
     * Register a route with an associated class and method.
     *
     * @param string $route     The URI (e.g. "/about")
     * @param string $className The fully-qualified class name responsible for handling the request
     * @param string $method    The method on the class to call
     */
    public function add($route, $className, $method)
    {
        $this->routes[$route] = [
            'class'  => $className,
            'method' => $method,
        ];
    }

    /**
     * Run the router: match the request path to a registered route, then dispatch.
     */
    public function run()
    {
        // Get the path portion of the URL (no query string).
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$path])) {
            $className = $this->routes[$path]['class'];
            $method    = $this->routes[$path]['method'];

            // Instantiate the controller class and call the method.
            $controller = new $className();

            if (method_exists($controller, $method)) {
                call_user_func([$controller, $method]);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "404 Method Not Found";
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}

/**
 * A sample controller class.
 */
class HomeController
{
    public function index()
    {
        echo "<h1>Welcome to the Home Page</h1>";
    }

    public function about()
    {
        echo "<h1>About Us</h1><p>This is the about page.</p>";
    }
}

/**
 * Example usage
 */
$router = new Router();

// Register routes with class + method
$router->add('/', HomeController::class, 'index');
$router->add('/about', HomeController::class, 'about');

$router->run();
