<?php
namespace App\Controllers;

class GeneratorController {

    private $modelDir;
    private $controllerDir;
    private $routeFile;
    private $data;

    public function __construct() {
        $this->modelDir = realpath(__DIR__ . '/../Models') . '/';
        $this->controllerDir = realpath(__DIR__ . '/../Controllers') . '/';
        $this->routeFile = realpath(__DIR__ . '/../Routes') . '/web.php';
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
        $this->generateRoutes($this->data['routes'] ?? []);

        echo json_encode(["status" => "ok", "message" => "Files generated successfully!"]);
    }

    private function generateModels($models) {
        foreach ($models as $model) {
            $className = ucfirst($model['name']);
            $tableName = $model['tableName'];

            $template = "<?php\n\nnamespace App\Models;\n\nuse App\Models\Model;\n\nclass $className extends Model\n{\n    protected static \$table = '$tableName';\n    protected static \$primaryKey = 'id';\n}\n";

            file_put_contents($this->modelDir . $className . ".php", $template);
        }
    }

    private function generateControllers($controllers) {
    foreach ($controllers as $controller) {
        $className = ucfirst($controller['name']);
        $modelClass = "\\App\\Models\\" . $className;
        $controllerClassName = $className . "Controller";
        $template = "<?php\nnamespace App\Controllers;\n\nuse App\Controllers\Controller;\n\nclass $controllerClassName extends Controller {\n\npublic function __construct() {\nparent::__construct($modelClass);\n}\n\n}\n";
        file_put_contents($this->controllerDir . $controllerClassName . ".php", $template);
    }
}


    private function generateRoutes($routes) {
        $routeLines = "";
        foreach ($routes as $route) {
            $method = strtoupper($route['method']);
            $path = $route['path'];
            $action = $route['controller'];
            $routeLines .= "\$router->add('$method', '$path', '$action');\n";
        }

        file_put_contents($this->routeFile, $routeLines, FILE_APPEND);
    }

    private function generateBasicMethods($name) {
        
    }

    public function selectAll() {

    }

    public function select($id) {

    }

    public function create() {

    }

    public function update($id = null) {

    }

    public function delete($id = null) {

    }
}
