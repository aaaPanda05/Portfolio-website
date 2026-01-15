<?php
namespace App\System;

class SystemState
{
    private const BACKEND_CONFIG = __DIR__ . '/../../config/backend.php';

    public static function get(): array
    {
        // State 1: not initialized
        if (!file_exists(self::BACKEND_CONFIG)) {
            return [
                'status' => 'not_initialized',
                'initialized' => false,
                'locked' => false,
            ];
        }

        $config = require self::BACKEND_CONFIG;

        $initialized = (bool) ($config['initialized'] ?? false);
        $locked = (bool) ($config['locked'] ?? false);

        // State 3: finalized
        if ($initialized && $locked) {
            return [
                'status' => 'finalized',
                'initialized' => true,
                'locked' => true,
            ];
        }

        // State 2: initialized but not locked
        if ($initialized) {
            return [
                'status' => 'initialized',
                'initialized' => true,
                'locked' => false,
            ];
        }

        // Invalid state fallback (defensive)
        return [
            'status' => 'invalid',
            'initialized' => false,
            'locked' => false,
        ];
    }
}
