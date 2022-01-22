<?php

namespace App\TasksQueue;

class JobRunner
{
    public function run($class, $method) {
        $taskQueue = TasksQueue::getInstance();
        var_dump($taskQueue->jobIssetAndRunning('ECatalog'));
        if(!$taskQueue->jobIssetAndRunning('ECatalog')) {
            $taskQueue->addJob(new Job('ECatalog', $class, $method));
        }
        $taskQueue->runLast();
    }
}