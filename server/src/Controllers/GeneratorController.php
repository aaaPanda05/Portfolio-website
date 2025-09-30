<?php
namespace App\Controllers;

use App\Types\Routes;

class GeneratorController {

    private $modelDir;
    private $controllerDir;
    private $routeCacheFile;
    private $data;

    public function __construct() {
        $this->modelDir = realpath(__DIR__ . '/../Models') . '/';
        $this->controllerDir = realpath(__DIR__ . '/../Controllers') . '/';
        $this->routeCacheFile = realpath(__DIR__ . '/../Routes') . '/routes_cache.php';
    }

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

        // Persist routes to disk
        $this->saveRoutesToCache();

        echo json_encode(["status" => "ok", "message" => "Files generated and routes saved successfully!"]);
    }

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

    private function saveRoutesToCache() {
        $routes = Routes::map();
        file_put_contents(
            $this->routeCacheFile,
            '<?php return ' . var_export($routes, true) . ';'
        );
    }

    public function checkCurrentRoutes() {
        // Load cached routes if available
        if (file_exists($this->routeCacheFile)) {
            Routes::$routes = require $this->routeCacheFile;
        }

        $routes = Routes::map();
        var_dump($routes);
    }
}