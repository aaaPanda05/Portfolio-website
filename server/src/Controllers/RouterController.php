<?php 

namespace App\Controllers;

use App\Types\Routes;

class RouterController {

    public function handleRequest($url, $method){

        //Initialize routes 
        Routes::init(__DIR__ . '/../Routes/routes_cache.php');

        //Retrieve all routes
        $routes = Routes::map();

        //Check if the route exists
        if (!array_key_exists($url, $routes[$method])) {
            http_response_code(404);
            echo "Route not found";
            exit;
        }

        //Extract controller and method
        [$controllerClass, $action] = $routes[$method][$url];

        if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
            (new $controllerClass())->$action();
        } else {
            http_response_code(404);
            echo "Controller or method not found";
        }
    }
}

?>