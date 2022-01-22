<?php

namespace App\TasksQueue;

class JobRunner
{
    public function run($class, $method) {
        $taskQueue = new TasksQueue();
        $lastJob = $taskQueue->getLastJob();


        if(!$taskQueue->isset('ECatalog')) {
            $taskQueue->insert('ECatalog', $class, $method);
            $taskQueue->run();
        } elseif(empty($lastJob) || $lastJob['status'] == TasksQueue::FAILED) {
            $taskQueue->insert('ECatalog', $class, $method);
            $taskQueue->run();
        }
    }
}