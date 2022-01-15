<?php


spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});
require_once 'functions.php';

class Runner {
    public function __construct(string $class, string $method)
    {
        (new $class)->$method();
    }
}
$runner = new Runner($argv[1], $argv[2]);
