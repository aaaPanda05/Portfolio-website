<?php

namespace App\System\Controllers;

use App\System\SystemState;
use App\Framework\ResponseHelper;

class SystemController {
    
    public function status() {
        ResponseHelper::json(SystemState::get());
    }
}

?>
