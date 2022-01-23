<?php

namespace App\TasksQueue;

class JobRunner
{
    private function randomName() : string{
        $validSims = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
        $name = "";
        for($i = 0; $i < rand(5, 12); $i++) {
            $name .= $validSims[rand(0, strlen($validSims) - 1)];
        }
        return $name;
    }

    public function run($class, $method, $data = []) {
        $taskQueue = TasksQueue::getInstance();

        if (!is_array($data)) {
            $data = [$data];
        }

        $taskQueue->addJob(new Job($this->randomName(), $class, $method, $data));
        $taskQueue->runLast();
    }

    public function runMany($class, $method, $data) {
        var_dump($data);
        foreach ($data as $item) {
            $this->run($class, $method, $item);
        }
    }

}