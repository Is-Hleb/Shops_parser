<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs", options={"collate"="utf8_general_ci"})
 */
class Job {

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
    }

    public function addLogs(array|string $logs, $type = 'error') {
        if(is_array($logs)) {
            foreach ($logs as $log) {
                $log = new Log($this, $log, $type);
                $this->logs[] = $log;
            }
        } else {
            $log = new Log($this, $logs, $type);
            $this->logs[] = $log;
        }
    }

    public function getContents() : array{
        $output = [];
        foreach ($this->contents as $content) {
            $output[] = $content->getContent();
        }
        return $output;
    }

    public function getJobTemplate() : JobTemplate {
        return $this->jobTemplate;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive(string $active): void
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function getExternalData(): array
    {
        return json_decode($this->externalData, true) ?? [];
    }

    /**
     * @param array $externalData
     */
    public function setExternalData(array $externalData): void
    {
        $this->externalData = json_encode($externalData);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return integer
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStarted(): \DateTime
    {
        return $this->started;
    }

    /**
     * @param \DateTime $started
     */
    public function setStarted(\DateTime $started): void
    {
        $this->started = $started;
    }

    /**
     * @param \DateTime $finished
     */
    public function setFinished(\DateTime $finished): void {
        $this->finished = $finished;
    }

    /**
     * @return \DateTime
     */
    public function getFinished(): \DateTime
    {
        return $this->finished;
    }

    public static function toJobsQueue(string $name, array $externalData, JobTemplate $jobTemplate) : self {
        $job = new self();
        $job->name = $name;
        $job->setExternalData($externalData);
        $job->setTemplate($jobTemplate);
        return $job;
    }

}