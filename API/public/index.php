<?php 

namespace API;

use API\Autoloader;
use API\Core\Router;

define('ROOT', dirname(__DIR__));
require_once ROOT . '/Autoloader.php';
Autoloader::register();

$app = new Router();

$app->start();