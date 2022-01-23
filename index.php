<?php
session_start();
ini_set("max_execution_time", 60 * 60 * 24 * 365);
const CACHE_ON = true;
const INDEX_FILE_PATH = __DIR__;
const LOGS_FILES_PATH = __DIR__ . '/logs';
define("CURRENT_ROUTE", empty($_GET['route']) ? '/' : $_GET['route']);
define("EXPLODED_ROUTES", explode('/', CURRENT_ROUTE));

require_once 'vendor/autoload.php';
require_once 'functions.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

const routes = [
    '/' =>  [
        \App\TasksQueue\JobRunner::class, 'runMany',
        [
            \App\ECatalog::class, 'run', [
            'category1',
            'category2',
            'category3',
            'category4'
        ]
        ]
    ],
    'add-job' => [
        \App\TasksQueue\JobRunner::class, 'runMany',
        [
            \App\ECatalog::class, 'run', [
                'category1',
                'category2',
                'category3',
                'category4'
            ]
        ]
    ],
    'cache/clear' => [\App\ECatalog::class, 'cacheClear'],
    'output' => [\App\BackgroundViewer::class, 'getOutput']
];


$class = routes[CURRENT_ROUTE][0];
$method = routes[CURRENT_ROUTE][1];
$args = routes[CURRENT_ROUTE][2] ?? [];

if(!is_dir('logs')) {
    mkdir('logs');
}

(new $class)->$method(...$args);

