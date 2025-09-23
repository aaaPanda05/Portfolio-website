<?php

namespace App\Types;


class Routes {
    //Default functions from controllers
    public static array $methodsRequest = [
        "selectAll" => "GET",
        "select"    => "GET",
        "delete"    => "DELETE"
    ]

    //Routes for certain methods
    public static array $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    private static function generateRoutes($className) {
        $classMethods = get_class_methods($className);

        // Get short class name and strip 'Controller' 
        $shortName = (new \ReflectionClass($className))->getShortName();
        if (str_ends_with($shortName, 'Controller')) {
            $shortName = substr($shortName, 0, -strlen('Controller'));
        }

        $shortName = strtolower($shortName); 

        foreach ($classMethods as $classMethod) {
            if (isset(self::$methodsRequest[$classMethod])) {
                $httpMethod = self::$methodsRequest[$classMethod];

                // Route key: controller/method 
                $routeKey = $shortName . '/' . strtolower($classMethod);

                self::$routes[$httpMethod][$routeKey] = [$className, $classMethod];
            }
        }
    }
}
?>