<?php
//roda no cmd do projeto php -S localhost:8000 -t public
require_once __DIR__ . '/../vendor/autoload.php';

use App\Routes\Router;

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
