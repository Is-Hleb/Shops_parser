<?php

namespace App\TasksQueue;

class TasksQueue
{
    private array $queue;
    private array $jobs;
    private bool $running = false;
    private const FILE = "queue.json";

    public const ENDED = 0;
    public const RUNNING = 1;
    public const WAITING = 2;
    public const FAILED = 3;


    public function __construct() {
        $path = __DIR__ . '/' . self::FILE;
        if (!file_exists($path)) {
            fopen($path, 'w');
        }
        if(!is_dir(LOGS_FILES_PATH . '/jobs')) {
            mkdir(LOGS_FILES_PATH . '/jobs');
        }
        $json = file_get_contents($path);
        $this->jobs = json_decode($json, true) ?? [];

        foreach ($this->jobs as $job) {
            if ($job['status'] == self::RUNNING) {
                $this->running = true;
            }
        }

        $this->queue = array_filter($this->jobs, function ($job) {
            return $job['status'] == self::WAITING;
        });
    }

    public function __destruct() {
        $path = __DIR__ . '/' . self::FILE;
        foreach ($this->jobs as &$job) {
            $job['lastSeen'] = \date('y-m-d h:m:s');
        }
        file_put_contents($path, json_encode($this->jobs));
    }

    public function isset(string $name): bool {
        foreach ($this->jobs as $job) {
            if($job['name'] == $name) {
                return true;
            }
        }
        return false;
    }


    public function insert($name, $class, $method) {
        //$command = "php Runner.php " . str_replace('\\', '-', $class) . " $method $name > /dev/null 2>/dev/null &";
        $logPath = LOGS_FILES_PATH . "/jobs/$name.log";
        if(!file_exists($logPath)) {
            fopen($logPath, "w");
        }
        $command = "php Runner.php " . str_replace('\\', '-', $class) . " $method $name > logs/jobs/$name.log &";
        $job = [
            'name' => $name,
            'status' => self::WAITING,
            'command' => $command
        ];

        $this->queue[] = $job;
        $this->jobs[$name] = $job;
    }

    public function run() {
        $job = &$this->queue[0];
        if (!$this->running) {
            exec($job['command'], $output);
            $job['status'] = self::RUNNING;
            $job['output'] = $output;
            $job['started'] = \date('y-m-d h:m:s');
            $this->jobs[$job['name']] = $job;
        }
    }

    public function stop($name, $status = self::ENDED, $output = "") {
        $this->jobs[$name]['status'] = $status;
        $this->jobs[$name]['output'] = $output;
    }

    public function getActiveJob() {
        $job = array_values(array_filter($this->jobs, function($job){
           return $job['status'] == self::RUNNING;
        }))[0] ?? [];

        if(empty($job)) {
            return [];
        }

        return $job;
    }

    public function getLastJob() {
        $dateArray = [];
        $jobs = array_filter($this->jobs, function($job){
           return isset($job['started']);
        });
        foreach($jobs as $key => $job){
            $dateArray[$key] = $job['started'];
        }
        array_multisort($dateArray, SORT_STRING, $jobs);
        $jobs = array_values($jobs);

        $job = $jobs[0] ?? [];
        if(empty($job)) {
            return $job;
        }

        return $job ?? [];
    }

    public static function format($job): array {
        if(empty($job)) {
            return [];
        }
        $job['status'] = match ($job['status']) {
            self::RUNNING => 'RUNNING',
            self::WAITING => 'WAITING',
            self::FAILED => 'FAILED',
            self::ENDED => 'ENDED',
        };
        return $job;
    }

    public function setContent($content) {

    }
}