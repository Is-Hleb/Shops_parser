<?php
session_start();

const CACHE_ON = true;
define("CURRENT_ROUTE", $_GET['route'] ?? '/');
define("EXPLODED_ROUTES", explode('/', CURRENT_ROUTE));

require_once 'vendor/autoload.php';
require_once 'functions.php';
spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

echo "<pre>";
var_dump(EXPLODED_ROUTES);
echo "</pre>";

const routes = [
    '/' => [\App\ECatalog::class, 'run'],
    'cache/clear' => [\App\ECatalog::class, 'cacheClear'],
];

$class = routes[CURRENT_ROUTE][0];
$method = routes[CURRENT_ROUTE][1];
(new $class)->$method();