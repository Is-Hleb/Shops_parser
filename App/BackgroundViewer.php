<?php

namespace App;

use App\TasksQueue\Job;
use App\TasksQueue\TasksQueue;

class BackgroundViewer
{
    public function getOutput() {
        $taskQueue = new TasksQueue();
        $job = $taskQueue->getActiveJob();
        echo $job['output'];
    }
}