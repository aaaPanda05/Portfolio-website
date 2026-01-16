<?php
namespace App\System;

class SystemSetup {
    private const BACKEND_CONFIG_FOLDER = __DIR__ . '/../../config/';

    public static function createBackendConfig(): void {
        // Ensure config folder exists
        if (!is_dir(self::BACKEND_CONFIG_FOLDER)) {
            mkdir(self::BACKEND_CONFIG_FOLDER, 0755, true);
        }

        $path = self::BACKEND_CONFIG_FOLDER . 'backend.php';

        // Do not overwrite if it already exists
        if (file_exists($path)) {
            return;
        }

        $content = <<<PHP
    <?php

    return [
        'status' => 'initialized',
        'initialized' => true,
        'locked' => false,
    ];
    PHP;

        file_put_contents($path, $content);
    }

}

?>