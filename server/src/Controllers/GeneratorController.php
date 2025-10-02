<?php
namespace App\Controllers;

use App\Types\Routes;
use App\Database\Migration;

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
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON"]);
            return;
        }

        $this->generateModels($this->data['models'] ?? []);
        $this->generateControllers($this->data['controllers'] ?? []);
        $this->generateRoutes($this->data['controllers'] ?? []);
        Migration::createTable($this->data['table']['name'], $this->data['table']['columns']);

        // Persist routes to disk
        $this->saveRoutesToCache();

        header('Content-Type: application/json');
        echo json_encode(["status" => "ok", "message" => "Files generated and routes saved successfully!"]);
    }

    /**
     * Generate model PHP files based on the provided model definitions.
     *
     * @param array $models List of models to generate
     */
    private function generateModels(array $models) {
        foreach ($models as $model) {
            $className = ucfirst($model['name']);
            $tableName = $model['tableName'];

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
    }

    /**
     * Generate controller PHP files for the given models.
     *
     * @param array $controllers List of controllers to generate
     */
    private function generateControllers(array $controllers) {
        foreach ($controllers as $controller) {
            $className = ucfirst($controller['name']);
            $modelClass = "\\App\\Models\\" . $className;
            $controllerClassName = $className . "Controller";

            $template = <<<PHP
                <?php
                namespace App\Controllers;

                use App\Controllers\Controller;

                class $controllerClassName extends Controller
                {
                    public function __construct()
                    {
                        parent::__construct("$modelClass");
                    }
                }

                PHP;

            file_put_contents($this->controllerDir . $controllerClassName . ".php", $template);
        }
    }

    /**
     * Generate routes for the given controllers.
     *
     * @param array $controllers List of controllers to register routes for
     */
    private function generateRoutes(array $controllers) {
        // Load base Controller first
        require_once __DIR__ . '/Controller.php';

        foreach ($controllers as $controller) {
            $className = "\\App\\Controllers\\" . ucfirst($controller['name']) . "Controller";
            $file = $this->controllerDir . ucfirst($controller['name']) . "Controller.php";

            if (file_exists($file)) {
                require_once $file; // load the generated controller
            }

            if (class_exists($className)) {
                Routes::generateRoutes($className);
            }
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
        header('Content-Type: application/json');
        echo json_encode($routes);
        exit; 
    }

}
