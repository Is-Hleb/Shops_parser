<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs", options={"collate"="utf8_general_ci"})
 */
class Job
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $name;

    /**
     * @ORM\Column(type="integer", options={"default":2})
     */
    protected int $status = 2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $command;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    protected bool $active = false;

    /**
     * @ORM\Column(type="json", name="external_data", nullable=false)
     */
    protected string $externalData;

    /**
     * @ORM\Column(type="datetime", name="started_at", nullable=true)
     */
    protected \DateTime $started;

    /**
     * @ORM\Column(type="datetime", name="finished_at", nullable=true)
     */
    protected \DateTime $finished;

    /**
     * @ORM\Column(type="datetime", name="added_at", nullable=true)
     */
    protected \DateTime $addedAt;

    /**
     * @ORM\OneToMany(targetEntity="Log", mappedBy="logs")
     */
    private mixed $logs;

    /**
     * One job has many content. This is the inverse side.
     * @ORM\OneToMany(targetEntity="JobContent", mappedBy="job")
     */
    private mixed $contents;

    /**
     * @ORM\ManyToOne(targetEntity="JobTemplate", inversedBy="jobTemplate")
     * @ORM\JoinColumn(name="job_template_id", referencedColumnName="id")
     */
    private JobTemplate $jobTemplate;

    public function setTemplate(JobTemplate $jobTemplate) {
        $jobTemplate->addJob($this);
        $this->jobTemplate = $jobTemplate;
    }

    public function addContent(mixed $value) {
        $this->contents[] = new JobContent($value, $this);

        global $entityManager;
        $entityManager->flush($this);
    }

    public static function find($id) {
        global $entityManager;
        return $entityManager->getReference(self::class, $id);
    }

    public function addLogs(array|string $logs, $type = 'error') {
        if (is_array($logs)) {
            foreach ($logs as $log) {
                $log = new Log($this, $log, $type);
                $this->logs[] = $log;
            }
        } else {
            $log = new Log($this, $logs, $type);
            $this->logs[] = $log;
        }

        global $entityManager;
        $entityManager->flush($this);
    }

    public function getContents(): array {
        $output = [];
        foreach ($this->contents as $content) {
            $output[] = $content->getContent();
        }
        return $output;
    }

    public function setExternalData($externalData): void {
        $this->externalData = json_encode($externalData);
    }

    public function getExternalData(): array {
        $json = json_decode($this->externalData, true) ?? [];

        if (empty($json)) {
            return [];
        }

        $output = [];
        foreach ($json as $item) {
            $output[$item['name']] = $item['value'];
        }
        return $output;
    }

    public static function addToQueue(string $name, array $externalData, JobTemplate $jobTemplate): Job {
        $jobIns = new self();

        $jobIns->name = $name;
        $jobIns->active = false;
        $jobIns->status = 2;
        $jobIns->addedAt = new \DateTime('NOW');

        $jobIns->setExternalData($externalData);
        $jobIns->setTemplate($jobTemplate);

        global $entityManager;
        $entityManager->persist($jobIns);
        $entityManager->flush();

        $command = "php runner.php "
            . str_replace('\\', '-', $jobIns->jobTemplate->getClass())
            . " {$jobIns->jobTemplate->getMethod()} {$jobIns->id}";

        $jobIns->command = $command;
        $entityManager->flush($jobIns);

        return $jobIns;
    }

    public function setActive() {
        $this->active = true;
        $this->status = 1;
        $this->started = new \DateTime('NOW');

        global $entityManager;
        $entityManager->flush($this);
    }

    public function setDisabled($status = 0) {
        $this->active = false;
        $this->status = $status;
        $this->finished = new \DateTime('NOW');

        global $entityManager;
        $entityManager->flush($this);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCommand(): string {
        return $this->command;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getToRead() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => match ($this->status) {
                0 => 'ended',
                1 => 'running',
                2 => 'waiting',
                3 => 'executed_with_error',
                default => 'executed_with_status: ' . $this->status
            },
        ];
    }
}