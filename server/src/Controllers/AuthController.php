<?php

namespace App\Controllers;

use App\Framework\ResponseHelper;

class AuthController {
    public static function status() {
        if(!isset($_SESSION['user'])) {
            return ResponseHelper::json(['loggedIn' => false]);
        }

        return ResponseHelper::json([
            'loggedIn' => true,
            'role' => $_SESSION['user']['role']
        ]);
    }
}

?>