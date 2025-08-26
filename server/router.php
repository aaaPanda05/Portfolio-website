<?php
require __DIR__ . '/vendor/autoload.php';

use App\Controllers\RouterController;

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'register';

$router = new RouterController();
$router->handleRequest($url);