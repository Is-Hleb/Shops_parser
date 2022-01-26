<?php

namespace App\TasksQueue;

use Doctrine\ORM\EntityManager;

class TasksQueue
{

    private static self $instance;

    private array $queueJobs;
    private array $jobs;
    private EntityManager $entityManager;

    protected function __clone() {
    }

    protected function __construct() {
        global $entityManager;
        $this->entityManager = $entityManager;


        $this->jobs = Job::loadAll();
        foreach ($this->jobs as &$job) {
            if($job->getStatus() === Job::WAITING) {
                $this->queueJobs[] = $job;
            }
        }

        $this->runLast();
    }

    public function runLast() {
        $this->updateData();
        if (!isset($this->queueJobs[0])) {
            return;
        }
        if (!$this->queueJobs[0]->isActive()) {
            $this->queueJobs[0]->execute();
        }
    }

    public function popFront(): void {
        array_unshift($this->queueJobs);
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