<?php

/**
 * This class is responsible for creating the router
 */
class Router
{
    private $routes = [];

    /**
     * This method defines the routes
     * 
     * @param array $routes The routes to be defined in key value pairs
     */
    public function define(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * This method redirects to the specified uri
     * 
     * @param string $uri
     * @return string Returns the file path to be opened
     */
    public function direct(string $uri): string
    {
        if (array_key_exists($uri, $this->routes)) {
            return $this->routes[$uri];
        }
        return "./pages/404.php";
    }
}
?>