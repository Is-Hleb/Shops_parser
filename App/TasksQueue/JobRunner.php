<?php

namespace App\TasksQueue;

class JobRunner
{
    public function run($class, $method, $data = []) {
        $taskQueue = TasksQueue::getInstance();
        if(!is_array($data)) {
            $data = [$data];
        }
        var_dump($taskQueue->jobIssetAndRunning('ECatalog'));
        if(!$taskQueue->jobIssetAndRunning('ECatalog')) {
            $taskQueue->addJob(new Job('ECatalog', $class, $method, $data));
            $taskQueue->addJob(new Job('ECatalog1', $class, $method, $data));
            $taskQueue->addJob(new Job('ECatalog2', $class, $method, $data));
        }
        $taskQueue->runLast();
    }
}