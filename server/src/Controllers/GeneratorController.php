<?php
namespace App\Controllers;

use App\Types\Routes;
use App\Database\Migration;
use App\Framework\ResponseHelper;

/**
 * Controller responsible for generating models, controllers, and routes.
 */
class GeneratorController {

    private $modelDir;
    private $controllerDir;
    private $routeCacheFile;
    private $data;

    /**
     * Constructor initializes directories and route cache file path.
     */
    public function __construct() {
        $this->modelDir = realpath(__DIR__ . '/../Models') . '/';
        $this->controllerDir = realpath(__DIR__ . '/../Controllers') . '/';
        $this->routeCacheFile = __DIR__ . '/../Routes/routes_cache.php';
        if (!file_exists(dirname($this->routeCacheFile))) {
            mkdir(dirname($this->routeCacheFile), 0777, true); // create folder if missing
        }
    }

    /**
     * Main endpoint to generate models, controllers, and routes from JSON input.
     */
    public function generate() {
        $this->data = json_decode(file_get_contents("php://input"), true);

        if (!$this->data) {
            ResponseHelper::json(["error" => "Invalid JSON", 400]);
            return;
        }

        $className  = $this->data['className'];
        $tableName  = $this->data['table']['name'];
        $properties = $this->data['properties'] ?? [];
        $methods    = $this->data['methods'] ?? [];

        $this->generateModel($className, $tableName);
        $this->generateController($className, $properties, $methods);
        $this->generateRoutes($className);

        Migration::createTable(
            $tableName,
            $this->data['table']['columns']
        );

        $this->saveRoutesToCache();

        ResponseHelper::json(["status" => "ok"]);
    }


    /**
     * Generate model PHP files based on the provided model definitions.
     *
     * @param array $models List of models to generate
     */
    private function generateModel(string $className, string $tableName) {
        $className = ucfirst($className);

        $template = <<<PHP
    <?php
    namespace App\Models;

    use App\Models\Model;

    class $className extends Model
    {
        protected static \$table = '$tableName';
        protected static \$primaryKey = 'id';
    }
    PHP;

        file_put_contents($this->modelDir . $className . ".php", $template);
    }


    /**
     * Generate controller PHP files for the given models.
     *
     * @param array $controllers List of controllers to generate
     */
    private function generateController(string $className, array $properties, array $methods) {
        $className = ucfirst($className);
        $modelClass = "\\App\\Models\\$className";
        $controllerClassName = $className . "Controller";

        $propertyTemplate = '';
        $methodTemplate = '';

        foreach ($properties as $property) {
            $propertyTemplate .= sprintf(
                "    %s \$%s;\n",
                $property['access'],
                $property['name']
            );
        }

        foreach ($methods as $method) {
            $methodTemplate .= sprintf(
                "\n    %s function %s()\n    {\n        //Place code here\n    }\n",
                $method['access'],
                $method['name']
            );
        }

    $template = <<<PHP
    <?php

    namespace App\Controllers;

    use App\Controllers\Controller;

    class $controllerClassName extends Controller
    {
    $propertyTemplate
        public function __construct()
        {
            parent::__construct($modelClass::class);
        }
    $methodTemplate
    }
    PHP;

        file_put_contents(
            $this->controllerDir . $controllerClassName . '.php',
            $template
        );
    }




    /**
     * Generate routes for the given controllers.
     *
     * @param array $controllers List of controllers to register routes for
     */
    private function generateRoutes(string $className) {
        require_once __DIR__ . '/Controller.php';

        $controllerClass = "\\App\\Controllers\\" . ucfirst($className) . "Controller";
        $file = $this->controllerDir . ucfirst($className) . "Controller.php";

        if (file_exists($file)) {
            require_once $file;
        }

        if (class_exists($controllerClass)) {
            Routes::generateRoutes($controllerClass);
        }
    }


    /**
     * Save current routes to the cache file.
     */
    private function saveRoutesToCache() {
        $routes = Routes::map();
        file_put_contents(
            $this->routeCacheFile,
            '<?php return ' . var_export($routes, true) . ';'
        );
    }

    /**
     * Output the currently registered routes (from cache if available).
     */
    public function checkCurrentRoutes() {
        // Load cached routes if available
        if (file_exists($this->routeCacheFile)) {
            Routes::$routes = require $this->routeCacheFile;
        }

        $routes = Routes::map();

        // Return as JSON
        ResponseHelper::json($routes);
        exit; 
    }

}
