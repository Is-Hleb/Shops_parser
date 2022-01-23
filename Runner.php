<?php

use \App\TasksQueue\TasksQueue;
define("JOB_NAME", $argv[3]);
const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});



require_once 'functions.php';
require_once 'vendor/autoload.php';

register_shutdown_function(function () {
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
        TasksQueue::getInstance()->getJobByName(JOB_NAME)->stop([], $message);
    }

    TasksQueue::getInstance()->runLast();
});

class Runner {
    private object $job;
    public function __construct(string $class, string $method)
    {
        ob_start();
        $class = str_replace("-", "\\", $class);
        $this->job = new $class(JOB_NAME);
        $data = TasksQueue::getInstance()->getJobByName(JOB_NAME)->getExternalData();
        if($data != null) {
            $this->job->$method(...$data);
        } else {
            $this->job->$method(...[]);
        }
    }
    public function __destruct() {
        $content = ob_get_contents();
        ob_end_clean();
        // current work stop
        TasksQueue::getInstance()->getJobByName(JOB_NAME)->stop($content);
    }
}

$runner = new Runner($argv[1], $argv[2]);

