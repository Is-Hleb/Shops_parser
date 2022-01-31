<?php

define("JOB_ID", $argv[3]);
const CACHE_ON = true;
const LOGS_FILES_PATH = __DIR__ . '/logs/jobs';


require __DIR__ . '/bootstrap.php';
require 'functions.php';
require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

class Runner
{
    private object $job;

    public function __construct(string $class, string $method) {

        $class = str_replace("-", "\\", $class);
        $this->job = new $class(JOB_ID);
        $this->job->$method();

    }
}

$runner = new Runner($argv[1], $argv[2]);
