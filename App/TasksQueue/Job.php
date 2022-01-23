<?php

namespace App\TasksQueue;

// Прочитать код, разделить очередь от работы
class Job
{
    public const ENDED = 0;
    public const RUNNING = 1;
    public const WAITING = 2;
    public const FAILED = 3;

    protected array $content = [];
    protected array $errors = [];
    protected array $externalData = [];

    /**
     * @return array
     */
    public function getExternalData(): array {
        return $this->externalData;
    }

    protected string $name;
    protected string $class;
    protected string $method;
    protected string $logFilePath;
    protected string $command;
    protected string $startedAt = "";
    protected string $stoppedAt = "";

    protected int $status;
    protected bool $active = false;
    protected TasksQueue $queue;

    public function __construct($name, $class, $method, $externalData = []) {
        $this->method = $method;
        $this->class = $class;
        $this->name = $name;
        $this->status = self::WAITING;
        $this->logFilePath = LOGS_FILES_PATH . "/jobs/$name.log";
        $this->externalData = $externalData;

        if(!is_dir(LOGS_FILES_PATH . '/jobs')) {
            mkdir(LOGS_FILES_PATH . '/jobs');
        }

        if(!file_exists($this->logFilePath)) {
            fopen($this->logFilePath, 'w');
        }

        $command = "php Runner.php "
            . str_replace('\\', '-', $this->class)
            . " $this->method $this->name "
            . "'" . json_encode($this->externalData) . "'"
            ." > logs/jobs/$this->name.log &"
        ;

        if(PHP_OS == 'WINNT') {
            $command = "START /B $command";
            $command = str_replace('&', '', $command);
        }

        $this->command = $command;

    }

    /**
     * @return array
     */
    public function getContent(): array {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStartedAt(): string {
        return $this->startedAt;
    }

    /**
     * @return string
     */
    public function getClass(): string {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @return int|null
     */
    public function getStatus(): int|null {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isActive(): bool {
        return $this->active;
    }

    public function execute() {
        $this->status = self::RUNNING;
        $this->active = true;
        exec($this->command, $output, $code);
        $this->startedAt = date(DATE_ATOM);
        TasksQueue::getInstance()->updateData();
    }

    public static function fromArray(array $job) : Job{
        $jobInst = new self($job['name'], $job['class'], $job['method']);
        foreach ($job as $key => $value) {
            $jobInst->$key = $value;
        }
        return $jobInst;
    }

    public function toArray() : array{
        $output['name'] = $this->name;
        $output['class'] = $this->class;
        $output['status'] = $this->status;
        $output['method'] = $this->method;
        $output['content'] = $this->content;
        $output['command'] = $this->command;
        $output['errors'] = $this->errors;
        $output['active'] = $this->status == self::RUNNING;
        $output['externalData'] = $this->externalData;
        if($this->startedAt != "") {
            $output['startedAt'] = $this->startedAt;
        }
        if($this->stoppedAt != "") {
            $output['stoppedAt'] = $this->stoppedAt;
        }

        return $output;
    }

    public function putContent(string|int|float $content) {
        $this->content[] = $content;
        TasksQueue::getInstance()->updateData();

    }

    public function stop(mixed $content = [], mixed $errors = []) {
        $this->status = self::ENDED;

        if(is_array($content)) {
            $this->content = array_merge($content, $this->content);
        } else {
            $this->content[] = $content;
        }

        if(is_array($errors)) {
            $this->errors = array_merge($errors, $this->errors);
        } else {
            $this->errors[] = $errors;
        }


        $this->active = false;
        $this->stoppedAt = date(DATE_ATOM);
        $this->status = empty($this->errors) ? self::ENDED : self::FAILED;

        TasksQueue::getInstance()->popFront(); // Delete current job from queue
        TasksQueue::getInstance()->updateData();
    }

}