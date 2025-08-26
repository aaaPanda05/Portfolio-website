<?php

namespace App\Types;


class Routes {
    // Routes with default method (index)
    public const TEST       = 'test';
    public const LOGIN          = 'login';

    // Routes with explicit methods
    public const LOGIN_USER     = 'login/user';

    // Map URLs to controller class and method
    public static function map(): array {
        return [
            self::TEST        => [\App\Controllers\TestController::class, 'index'],
            self::LOGIN           => [\App\Controllers\LoginController::class, 'index'],
            self::LOGIN_USER      => [\App\Controllers\LoginController::class, 'user'],
        ];
    }

    // Return all paths
    public static function all(): array {
        return array_keys(self::map());
    }
}
?>