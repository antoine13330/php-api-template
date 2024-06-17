<?php

namespace AntoineD\Api;

require_once "Routes/routes.php";
require_once "functions.php";
require_once "Controllers/Controller.php";
class Router
{
    private string $url;
    private string $method;

    public function __construct($url, $method)
    {
        $this->url = $url;
        $this->method = $method;
    }

    public function getAvailableRoutes()
    {
        return routes();
    }

    public function render()
    {
        $routes = $this->getAvailableRoutes();
        // isolate the resource name from the url
        $this->url = explode('/', $this->url)[1];
        if (!isset($routes[$this->url])) {
            echo send(["error" => 'Route not found'], 404);
            return;
        }
        $route = $routes[$this->url];


        $table = $route['table'];


        $this->execute($table, $this->method);
    }

    // execute function will be responsible for calling the correct method from the generic controller
    // the generic controller has to be initialized with the correct model given from the table name
    private function execute($table, $method)
    {
        $controller = new Controllers\GenericController($table);
        return $controller->handleRequest();
    }
}

?>
