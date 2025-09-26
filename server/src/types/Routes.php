<?php

namespace App\Types;

class Routes {
    // Cache file path
    private static string $cacheFile = '';

    // Default methods mapped to HTTP verbs
    public static array $methodsRequest = [
        "selectAll" => "GET",
        "select"    => "GET",
        "delete"    => "DELETE"
    ];

    // In-memory routes
    public static array $routes = [
        "GET" => [
            "generator/generate" => [\App\Controllers\GeneratorController::class, "generate"],
            "generator/routes" => [\App\Controllers\GeneratorController::class, "checkCurrentRoutes"]
        ],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    // Initialize cache file
    public static function init(string $cacheFilePath) {
        self::$cacheFile = $cacheFilePath;

        if (file_exists(self::$cacheFile)) {
            $cached = require self::$cacheFile;
            if (is_array($cached)) {
                self::$routes = $cached;
            }
        }
    }

    // Generate routes for a controller class
    public static function generateRoutes(string $className) {
        if (!class_exists($className)) {
            return; // class not loaded yet
        }

        $classMethods = get_class_methods($className);

        $shortName = (new \ReflectionClass($className))->getShortName();
        if (str_ends_with($shortName, 'Controller')) {
            $shortName = substr($shortName, 0, -strlen('Controller'));
        }
        $shortName = strtolower($shortName);

        foreach ($classMethods as $classMethod) {
            if (isset(self::$methodsRequest[$classMethod])) {
                $httpMethod = self::$methodsRequest[$classMethod];
                $routeKey = $shortName . '/' . $classMethod;
                self::$routes[$httpMethod][$routeKey] = [$className, $classMethod];
            }
        }
    }

    // Save current routes to cache file
    public static function saveCache() {
        if (!self::$cacheFile) return;

        file_put_contents(
            self::$cacheFile,
            '<?php return ' . var_export(self::$routes, true) . ';'
        );
    }

    // Return all routes, optionally scanning controllers folder
    public static function map(): array {
        // If cache exists, load it (
        if (self::$cacheFile && file_exists(self::$cacheFile)) {
            $cached = require self::$cacheFile;
            if (is_array($cached)) {
                self::$routes = array_merge_recursive(self::$routes, $cached);
            }
        }
        return self::$routes;
    }
}
