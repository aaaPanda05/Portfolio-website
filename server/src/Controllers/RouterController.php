<?php 

namespace App\Controllers;


class RouterController {

    public function handleRequest($url){
        $parts = explode("/", $url);

        $controllerName = ucfirst($parts[0] ?? 'Default'); 
        $method = $parts[1] ?? 

        $controllerClass = "App\\Controllers\\{$controllerName}Controller";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                $controller->index();
            }
        } else {
            http_response_code(404);
            echo "Controller not found";
        }
    }

}

?>