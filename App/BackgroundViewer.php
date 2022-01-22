<?php

namespace App;

use App\TasksQueue\Job;
use App\TasksQueue\TasksQueue;

class BackgroundViewer
{
    public function getOutput() {
        $taskQueue = TasksQueue::getInstance();
        $job = $taskQueue->getLastActive();
        foreach ($job->getContent() as $item) {
            echo $item . '<br>';
        }
    }
}