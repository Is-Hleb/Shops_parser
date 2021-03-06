<?php
session_start();
ini_set("max_execution_time", 60 * 60 * 24 * 365);

const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs';
define("CURRENT_ROUTE", empty($_GET['route']) ? '/' : $_GET['route']);
define("EXPLODED_ROUTES", explode('/', CURRENT_ROUTE));
define("REQUEST_METHOD", $_SERVER['REQUEST_METHOD']);

require_once __DIR__ . '/bootstrap.php';
require_once 'functions.php';
require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

const routes = [
    "GET" => [
        'settings' => [\App\Controllers\SettingsController::class, 'all'],
        'settings/collections' => [\App\Controllers\SettingsController::class, 'allCollections'],

        // Jobs Urls
        'job/templates' => [\App\Controllers\JobTemplateController::class, 'all'],
        'jobs' => [\App\Controllers\JobController::class, 'all'],
        'job/info' => [\App\Controllers\JobController::class, 'info'],
        'job/restart' => [\App\Controllers\JobController::class, 'restart'],

        // JSON CONTENT
        'download/json/content' => [\App\Controllers\ContentController::class, 'downloadAsJson']
    ],
    "PUT" => [
        'setting/edit' => [\App\Controllers\SettingsController::class, 'edit'],
    ],
    "POST" => [
        'setting' => [\App\Controllers\SettingsController::class, 'create'],
        'setting/delete' => [\App\Controllers\SettingsController::class, 'delete'],

        // Jobs Urls
        'job/template' => [\App\Controllers\JobTemplateController::class, 'create'],
        'job' => [\App\Controllers\JobController::class, 'create'],
        'jobs/delete' => [\App\Controllers\JobController::class, 'deleteJobs']
    ],
    "DELETE" => [
        'job' => [\App\Controllers\JobController::class, 'delete']
    ]
];

$routes = routes[REQUEST_METHOD];
$class = $routes[CURRENT_ROUTE][0] ?? null;
$method = $routes[CURRENT_ROUTE][1] ?? null;
$args = $routes[CURRENT_ROUTE][2] ?? [];

if(!$class || !$method) {
    echo "NOT FOUND";
    exit();
}

if (!is_dir('logs')) {
    mkdir('logs');
    if(!is_dir('logs/jobs')) {
        mkdir('logs/jobs');
    }
}

(new $class)->$method(...$args);

