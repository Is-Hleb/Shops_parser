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
    protected string $name;
    protected string $class;
    protected string $method;
    protected string $logFilePath;
    protected string $command;
    protected int $status;
    protected bool $active = false;
    protected TasksQueue $queue;

    public function __construct($name, $class, $method) {
        $this->method = $method;
        $this->class = $class;
        $this->name = $name;
        $this->status = self::WAITING;
        $this->logFilePath = LOGS_FILES_PATH . "/jobs/$name.log";

        if(!is_dir(LOGS_FILES_PATH . '/jobs')) {
            mkdir(LOGS_FILES_PATH . '/jobs');
        }

        if(!file_exists($this->logFilePath)) {
            fopen($this->logFilePath, 'w');
        }

        $command = "php Runner.php "
            . str_replace('\\', '-', $this->class)
            . " $this->method $this->name > logs/jobs/$this->name.log &"
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

        return $output;
    }

    public function putContent(string|int|float $content) {
        $this->content[] = $content;
        $taskQueue = TasksQueue::getInstance();
        $taskQueue->updateData();
    }

    public function stop(mixed $content = [], mixed $errors = []) {
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

        $this->status = self::ENDED;
    }

}