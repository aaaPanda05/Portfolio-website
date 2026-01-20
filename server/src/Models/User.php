<?php
namespace App\Models;

use App\Models\Model;

class User extends Model
{
    protected static $table = 'user';
    protected static $primaryKey = 'id';
}