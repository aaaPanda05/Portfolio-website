<?php

namespace App\System\Controllers;

use App\System\SystemState;
use App\System\SystemSetup;
use App\Framework\ResponseHelper;

class SystemController {
    
    public function status() {
        ResponseHelper::json(SystemState::get());
    }

    public function finalize() {
        $input = ResponseHelper::getInput();

        if (!$input || !isset($input['password']) || !is_string($input['password']) || empty($input['password'])) {
            http_response_code(400);
            ResponseHelper::json(['error' => 'Password is required']);
            return;
        }

        $plainPassword = $input['password'];

        SystemSetup::finalizeSetup($plainPassword);

        ResponseHelper::json([
            'status' => 'finalized',
            'initialized' => true,
            'locked' => true,
        ]);
    }
}

?>
