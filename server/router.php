<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\RouterController;

// Point to the config directory where .env is located
$dotenv = Dotenv::createImmutable(__DIR__ . '/config');
$dotenv->load();

require __DIR__ . '/config/config.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'register';

$router = new RouterController();
$router->handleRequest($url);