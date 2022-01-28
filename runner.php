<?php

define("JOB_ID", $argv[3]);
const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs/jobs';


require_once 'bootstrap.php';
require_once 'functions.php';
require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});


class runner
{
    private object $job;

    public function __construct(string $class, string $method) {

        $class = str_replace("-", "\\", $class);
        $this->job = new $class(JOB_ID);
        $this->job->$method();

    }
}

$runner = new runner($argv[1], $argv[2]);
