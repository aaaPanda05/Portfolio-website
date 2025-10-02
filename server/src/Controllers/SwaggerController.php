<?php

namespace App\Controllers;

use App\Types\Routes;

class SwaggerController {

    /**
     * Returns created JSON of routes for swagger
     */
    public function json() {
        header("Content-Type: application/json");
        echo Routes::toOpenApiJson();
    }
}
