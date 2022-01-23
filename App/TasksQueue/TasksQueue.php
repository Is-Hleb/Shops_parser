<?php

namespace App\TasksQueue;

class TasksQueue
{

    private static self $instance;

    private array $queueJobs;
    private array $jobs;
    private string $queueFile;

    protected function __clone() {
    }

    protected function __construct() {
        $this->queueFile = __DIR__ . '/queue.json';
        $this->jobs = [];
        $this->queueJobs = [];

        if (!file_exists($this->queueFile)) {
            fopen($this->queueFile, 'w');
        }
        $json = json_decode(file_get_contents($this->queueFile, 'w'), true) ?? [];
        if (!empty($json)) {
            foreach ($json as $jobArray) {
                $this->jobs[] = Job::fromArray($jobArray);
            }
            if (!empty($this->jobs)) {
                foreach ($this->jobs as $job) {
                    if ($job->getStatus() == Job::WAITING) {
                        $this->queueJobs[] = $job;
                    }
                }
            }
        }
    }

    // TODO replace as saving jobs into DB
    public function __destruct() {
        $this->updateData();
    }

    public function addJob(Job $job) {
        $this->queueJobs[count($this->queueJobs)] = $job;
        $this->jobs[] = $job;
    }

    public function runLast() {
        if(!isset($this->queueJobs[0])) {
            return;
        }
        if (!$this->queueJobs[0]->isActive()) {
            $this->queueJobs[0]->execute();
        }
    }

    public function popFront() : void {
        array_unshift($this->queueJobs);
    }

    public function getLast(): Job {
        return $this->queueJobs[0];
    }

    public function getLastActive(): Job|null {
        foreach ($this->jobs as $job) {
            if ($job->isActive()) {
                return $job;
            }
        }

        return null;

    }

    public function someJobIsRunning(): bool {
        foreach ($this->jobs as $job) {
            if($job->isActive()) {
                return true;
            }
        }
        return false;
    }

    public function getJobByName(string $name): Job|null {
        foreach ($this->jobs as &$job) {
            if ($job->getName() == $name) {
                return $job;
            }
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a Queue twice.");
    }

    public function jobIssetAndRunning($name): bool {
        $job = $this->getJobByName($name);
        return $job != null;
    }

    public function updateData() : void{
        $output = [];
        foreach ($this->jobs as $job) {
            $output[] = $job->toArray();
        }
        $output = json_encode($output);
        file_put_contents($this->queueFile, $output);
    }

    public static function getInstance(): TasksQueue {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}