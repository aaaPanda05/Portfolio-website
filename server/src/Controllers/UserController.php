<?php

namespace App\Controllers;

use App\Controllers\Controller;

class UserController extends Controller
{
    public $id;

    public function __construct()
    {
        parent::__construct(\App\Models\User::class);
    }

    public function login()
    {
        //Place code here
    }

}