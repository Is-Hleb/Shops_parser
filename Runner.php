<?php

use \App\TasksQueue\TasksQueue;
define("JOB_ID", $argv[3]);
const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs';


require_once 'bootstrap.php';
require_once 'functions.php';
require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});

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
        TasksQueue::getInstance()->getJobById(JOB_ID)->stop([], $message);
    }

    TasksQueue::getInstance()->runLast();
});

class Runner extends \App\TasksQueue\Job {
    private object $job;
    public function __construct(string $class, string $method)
    {
        TasksQueue::getInstance()->getJobById(JOB_ID)->runned();
        $class = str_replace("-", "\\", $class);
        $this->job = new $class(JOB_ID);
        $data = TasksQueue::getInstance()->getJobById(JOB_ID)->getContent();
        if($data != null) {
            $this->job->$method(...$data);
        } else {
            $this->job->$method(...[]);
        }
    }
    public function __destruct() {
        // current work stop
        TasksQueue::getInstance()->getJobById(JOB_ID)->stop();
    }
}

$runner = new Runner($argv[1], $argv[2]);

