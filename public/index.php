<?php
// public/index.php
require_once __DIR__ . '/../vendor/autoload.php';

use Omni\Core\Core\Router\Router;
use Omni\Core\Modules\Auth\Controllers\AuthController;

session_start();

$router = new Router();

// Define routes
$router->get('/', function() {
    if (isset($_SESSION['user_id'])) {
        echo "<h1>Welcome back, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
        echo "<a href='/logout'>Logout</a>";
    } else {
        echo "<h1>Welcome to the site</h1>";
        echo "<a href='/login'>Login</a>";
    }
});


$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'processLogin']);
$router->get('/logout', [AuthController::class, 'logout']);

// Handle the request
$router->handle();