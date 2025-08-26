<?php 

namespace App\Controllers;

use App\Types\Routes;

class RouterController {

    public function handleRequest($url){
        //Retrieve all routes
        $routes = Routes::map();

        //Check if the route exists
        if (!in_array($url, Routes::all())) {
            http_response_code(404);
            echo "Route not found";
            exit;
        }

        //Extract controller and method
        [$controllerClass, $method] = $routes[$url];

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            (new $controllerClass())->$method();
        } else {
            http_response_code(404);
            echo "Controller or method not found";
        }
    }
}

?>