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
        SystemSetup::finalizeSetup($input['password']);

        ResponseHelper::json([
            'status' => 'finalized',
            'initialized' => true,
            'locked' => true,
    ]);
}

}

?>
