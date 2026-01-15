<?php
namespace App\System;

use App\System\Controllers\SystemController;

class SystemRouter
{
    private array $routes = [
        '__system/status'   => [SystemController::class, 'status'],
        '__system/finalize' => [SystemController::class, 'finalize'],
    ];

    public function handleRequest(string $url): void
    {
        if (!isset($this->routes[$url])) {
            http_response_code(404);
            echo 'System route not found';
            return;
        }

        [$controller, $action] = $this->routes[$url];

        if (!class_exists($controller) || !method_exists($controller, $action)) {
            http_response_code(500);
            echo 'System controller misconfigured';
            return;
        }

        (new $controller())->$action();
    }
}
