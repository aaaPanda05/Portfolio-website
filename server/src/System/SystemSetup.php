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

    public static function finalizeSetup($plainPassword): void {
        $path = self::BACKEND_CONFIG_FOLDER . 'backend.php';

        if (!file_exists($path)) {
            throw new \RuntimeException('System not initialized');
        }

        $config = require $path;

        if (!empty($config['locked'])) {
            throw new \RuntimeException('System already finalized');
        }

        $config['status'] = 'finalized';
        $config['locked'] = true;
        $config['admin_password_hash'] = password_hash($plainPassword, PASSWORD_DEFAULT);

        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents($path, $content);
    }
    
}

?>