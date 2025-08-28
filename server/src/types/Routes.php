<?php

namespace App\Types;


class Routes {
    // GET routes
    public const TEST       = 'test';

    // POST routes
    public const LOGIN          = 'login';

    // Map URLs to controller class and method
    public static function map(): array {
        return [
            //Fetching data
            "GET" => [
                self::TEST        => [\App\Controllers\TestController::class, 'index']
            ],
            //Sends data
            "POST" => [
                self::LOGIN           => [\App\Controllers\LoginController::class, 'index']
            ],
            //Updating data
            "PUT" => [],
            //Deleting data
            "DELETE" => []
        ];
    }

    // Return all paths
    public static function all(): array {
        return array_keys(self::map());
    }
}
?>