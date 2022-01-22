<?php

define("JOB_NAME", $argv[3]);
const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

$taskQueue = new \App\TasksQueue\TasksQueue();

require_once 'functions.php';
require_once 'vendor/autoload.php';

register_shutdown_function(function () use ($taskQueue) {
    $errFile = "unknown file";
    $errStr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errLine = 0;

    $error = error_get_last();

    if($error !== NULL) {
        $errno   = $error["type"];
        $errFile = $error["file"];
        $errLine = $error["line"];
        $errStr  = $error["message"];

        $message = "$errFile : $errLine --- $errStr \n({$errno})";
        $taskQueue->stop(JOB_NAME, $taskQueue::FAILED, $message);
    }
});


class Runner {
    private object $job;
    public function __construct(string $class, string $method)
    {
        $class = str_replace("-", "\\", $class);
        $this->job = new $class;
        $this->job->$method();
    }
}

ob_start();

$runner = new Runner($argv[1], $argv[2]);
$content = ob_get_contents();

$taskQueue->stop(JOB_NAME, $taskQueue::ENDED, $content);
ob_end_clean();

