<?php

namespace App\TasksQueue;

use Doctrine\ORM\EntityManager;

class TasksQueue
{

    private static self $instance;

    private array $queueJobs;
    private array $jobs;

    protected function __clone() {
    }

    protected function __construct() {

        $this->jobs = Job::loadAll();
        foreach ($this->jobs as &$job) {
            if($job->getStatus() === Job::WAITING) {
                $this->queueJobs[] = $job;
            }
        }

    }


    public function popFront(): void {
        $this->updateData();
    }

    public function getLast(): Job {
        return $this->queueJobs[0];
    }

    public function getLastActive(): Job|null {
        $this->updateData();
        foreach ($this->jobs as $job) {
            if ($job->isActive()) {
                return $job;
            }
        }

        return null;

    }

    public function someJobIsRunning(): bool {
        $this->updateData();
        foreach ($this->jobs as $job) {
            if ($job->isActive()) {
                return true;
            }
        }
        return false;
    }

    public function getJobById(string $id): Job|null {
        $this->updateData();
        foreach ($this->jobs as &$job) {
            if ($job->getId() == $id) {
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

    public function jobIssetAndRunning($id): bool {
        $job = $this->getJobById($id);
        return $job != null;
    }

    public function updateData(): void {
        $this->jobs = Job::loadAll();
        foreach ($this->jobs as &$job) {
            if($job->getStatus() === Job::WAITING) {
                $this->queueJobs[] = $job;
            }
        }
    }

    public static function getInstance(): TasksQueue {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}