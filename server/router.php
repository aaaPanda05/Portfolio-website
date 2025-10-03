<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\RouterController;

// Point to the config directory where .env is located
$dotenv = Dotenv::createImmutable(__DIR__ . '/config');
$dotenv->load();

require __DIR__ . '/config/config.php';

// --- CORS headers ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : ''; 
$method = $_SERVER['REQUEST_METHOD'];
$router = new RouterController();
$router->handleRequest($url, $method);