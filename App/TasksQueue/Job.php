<?php

namespace App\TasksQueue;

// Прочитать код, разделить очередь от работы
use App\Models\JobTemplate;
use Doctrine\ORM\EntityManager;
use App\Models\Job as JobModel;

class Job
{
    public const ENDED = 0;
    public const RUNNING = 1;
    public const WAITING = 2;
    public const FAILED = 3;


    protected TasksQueue $queue;
    protected EntityManager $entityManager;
    protected \App\Models\Job $dbInstance;
    protected JobTemplate $template;


    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public static function setJob(JobModel $job) : self {
        global $entityManager;
        $jobIns = new self();
        $jobIns->entityManager = $entityManager;

        $jobIns->dbInstance = $job;
        $jobIns->template = $jobIns->dbInstance->getJobTemplate();

        $jobIns->dbInstance->setStatus(self::WAITING);


        $jobIns->entityManager->persist($job);
        $jobIns->entityManager->flush();

        return $jobIns;
    }

    public static function loadAll() : array{
        global $entityManager;
        $dbJobs = $entityManager->getRepository(\App\Models\Job::class)->findAll();
        $output = [];

        foreach ($dbJobs as &$dbJob) {
            $job = new self();
            $job->dbInstance = $dbJob;
            $job->template = $dbJob->getJobTemplate();
            $job->entityManager = $entityManager;
            $output[] = $job;
        }
        return $output;
    }

    public function updateDbInstance(): void {
        $this->entityManager->flush($this->dbInstance);
    }

    /**
     * @return array
     */
    public function getContent(): array {
        return $this->dbInstance->getContents();
    }

    public function getId() : int {
        return $this->dbInstance->getId();
    }

    /**
     * @return string
     */
    public function getStartedAt(): string {
        return $this->dbInstance->getStarted();
    }

    /**
     * @return string
     */
    public function getClass(): string {
        return $this->dbInstance->getJobTemplate()->getClass();
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->template->getMethod();
    }

    /**
     * @return int|null
     */
    public function getStatus(): int|null {
        return $this->dbInstance->getStatus();
    }

    /**
     * @return bool
     */
    public function isActive(): bool {
        return $this->dbInstance->getActive();
    }

    public function execute() {

        $command = "php Runner.php "
            . str_replace('\\', '-', $this->template->getClass())
            . " {$this->template->getMethod()} {$this->dbInstance->getId()}"
            . " > logs/jobs/{$this->dbInstance->getName()}.log &";

        if (PHP_OS == 'WINNT') {
            $command = "START /B $command";
            $command = str_replace('&', '', $command);
        }

        $this->dbInstance->setCommand($command);


        exec($this->dbInstance->getCommand(), $output, $code);
        $this->updateDbInstance();
    }

    protected function runned() : void {
        $this->dbInstance->setActive(true);
        $this->dbInstance->setStatus(self::RUNNING);
        $this->dbInstance->setStarted(new \DateTime());

        $this->updateDbInstance();
    }

    public function putContent(string|int|float $content) {
        $this->dbInstance->addContent($content);
        $this->updateDbInstance();
    }

    public function addLog(string|array $log, string $type = 'error') {
        $this->dbInstance->addLogs($log, $type);
        $this->updateDbInstance();
    }

    public function getExternalData() : array {
        return $this->dbInstance->getExternalData();
    }

    public function stop(mixed $content = [], mixed $errors = []) {

        if(!empty($content)) {
            $this->dbInstance->addContent($content);
        }

        if(!empty($errors)) {
            $this->dbInstance->addLogs($errors);
        }


        $this->dbInstance->setActive(false);
        $this->dbInstance->setFinished(new \DateTime());
        $this->dbInstance->setStatus(
            empty($this->errors) ? self::ENDED : self::FAILED
        );

        $this->updateDbInstance();

        TasksQueue::getInstance()->popFront(); // Delete current job from queue
        TasksQueue::getInstance()->updateData();
    }

}