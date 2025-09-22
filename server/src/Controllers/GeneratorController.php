<?php
namespace App\Controllers;

class GeneratorController {

    private $modelDir;
    private $controllerDir;
    private $routeFile;

    public function __construct() {
        $this->modelDir = realpath(__DIR__ . '/../Models') . '/';
        $this->controllerDir = realpath(__DIR__ . '/../Controllers') . '/';
        $this->routeFile = realpath(__DIR__ . '/../Routes') . '/web.php';
    }

    public function generate() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON"]);
            return;
        }

        $this->generateModels($data['models'] ?? []);
        $this->generateControllers($data['controllers'] ?? []);
        $this->generateRoutes($data['routes'] ?? []);

        echo json_encode(["status" => "ok", "message" => "Files generated successfully!"]);
    }

    private function generateModels($models) {
        foreach ($models as $model) {
            $className = ucfirst($model['name']);
            $fields = $model['fields'] ?? [];

            $properties = "";
            foreach ($fields as $field) {
                $properties .= "    public \$$field;\n";
            }

            $template = "<?php\n\nclass $className {\n$properties\n    public function __construct(\$data = []) {\n";
            foreach ($fields as $field) {
                $template .= "        \$this->$field = \$data['$field'] ?? null;\n";
            }
            $template .= "    }\n}\n";

            file_put_contents($this->modelDir . $className . ".php", $template);
        }
    }

    private function generateControllers($controllers) {
        foreach ($controllers as $controller) {
            $className = ucfirst($controller['name']);
            $methods = $controller['methods'] ?? ["index"];

            $methodsCode = "";
            foreach ($methods as $method) {
                $methodsCode .= "    public function $method() {\n        // TODO: implement $method\n    }\n\n";
            }

            $template = "<?php\n\nclass $className {\n\n$methodsCode}\n";
            file_put_contents($this->controllerDir . $className . ".php", $template);
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
}
