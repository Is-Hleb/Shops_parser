<?php
session_start();
ini_set("max_execution_time", 60 * 60 * 24 * 365);
const CACHE_ON = true;
define("CURRENT_ROUTE", $_GET['route'] ?? '/');
define("EXPLODED_ROUTES", explode('/', CURRENT_ROUTE));

require_once 'vendor/autoload.php';
require_once 'functions.php';
spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

const routes = [
    '/' => [\App\ECatalog::class, 'run'],
    'cache/clear' => [\App\ECatalog::class, 'cacheClear'],
];

$class = routes[CURRENT_ROUTE][0];
$method = routes[CURRENT_ROUTE][1];


exec("php Runner.php $class $method", $output);
$ans = json_decode($output[0], true);
var_dump($ans);
$start = 0;
while(isset($ans['error'])) {
    sleep(2);
    exec("php Runner.php $class $method", $output);
    $ans = json_decode($output[0], true);
    var_dump($ans['error'], $start++);
}