<?php
namespace App\Controllers;

use App\Controllers\Controller;

class PandaController extends Controller
{
    public function __construct()
    {
        parent::__construct("\App\Models\Panda");
    }
}
