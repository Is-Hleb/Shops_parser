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
    protected string $status;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $command;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    protected string $active;

    /**
     * @ORM\Column(type="json", name="external_data", nullable=false)
     */
    protected string $externalData;

    /**
     * @ORM\Column(type="datetime", name="started_at", nullable=true)
     */
    protected string $started;

    /**
     * @ORM\Column(type="datetime", name="finished_at", nullable=true)
     */
    protected string $finished;

    /**
     * @ORM\OneToMany(targetEntity="Log", mappedBy="logs")
     */
    private array $logs;

    /**
     * @ORM\ManyToOne(targetEntity="JobTemplate", inversedBy="jobTemplate")
     * @ORM\JoinColumn(name="job_template_id", referencedColumnName="id")
     */
    private JobTemplate $jobTemplate;

    public function setTemplate(JobTemplate $jobTemplate) {
        $jobTemplate->addJob($this);
        $this->jobTemplate = $jobTemplate;
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
    public function getStarted(): string
    {
        return $this->started;
    }

    /**
     * @param string $started
     */
    public function setStarted(string $started): void
    {
        $this->started = $started;
    }

    /**
     * @param string $finished
     */
    public function setFinished(string $finished): void {
        $this->finished = $finished;
    }

    /**
     * @return string
     */
    public function getFinished(): string
    {
        return $this->finished;
    }

    public function getToRead() {
        return [
            $this->id,
            $this->name,
            $this->status,
            $this->command,
            $this->active,
            $this->externalData,
            $this->started,
            $this->finished
        ];
    }

}