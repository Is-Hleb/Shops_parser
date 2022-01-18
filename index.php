<?php
session_start();
ini_set("max_execution_time", 60 * 60 * 24 * 365);
const CACHE_ON = true;
const INDEX_FILE_PATH = __DIR__;
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

use App\TasksQueue\TasksQueue;

$taskQueue = new TasksQueue();
$lastJob = $taskQueue->getLastJob();
d($lastJob );
if(!$taskQueue->isset('ECatalog')) {
    $taskQueue->insert('ECatalog', $class, $method);
    $taskQueue->run();
}
$active = $taskQueue->getActiveJob();
if($lastJob['status'] == TasksQueue::FAILED) {
    $taskQueue->insert('ECatalog', $class, $method);
    $taskQueue->run();
}
d($active);

// $process = new PhpProcess(file_get_contents( "Runner.php"));
// $process->setOptions(['create_new_console' => true]);
// $process->start();
// $process->run();
// $process->start();