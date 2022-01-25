<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class Job {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $name;

    /**
     * @ORM\Column(type="integer", options={"default":2})
     */
    protected string $status;

    /**
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $class;

    /**
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $method;

    /**
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $command;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     */
    protected string $active;

    /**
     * @ORM\Column(type="json", name="external_data", nullable="false")
     */
    protected string $externalData;

    /**
     * @ORM\Column(type="datetime", name="started_at")
     */
    protected string $started;

    /**
     * @ORM\Column(type="datetime", name="finished_at", nullable="false")
     */
    protected string $finished;

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
     * @return string
     */
    public function getExternalData(): string
    {
        return $this->externalData;
    }

    /**
     * @param string $externalData
     */
    public function setExternalData(string $externalData): void
    {
        $this->externalData = $externalData;
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
     * @return string
     */
    public function getClass(): string {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void {
        $this->name = $method;
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
     * @return string
     */
    public function getFinished(): string
    {
        return $this->finished;
    }

    /**
     * @param string $finished
     */
    public function setFinished(string $finished): void
    {
        $this->finished = $finished;
    }

    public function getToRead() {
        return [
            $this->id,
            $this->name,
            $this->class,
            $this->status,
            $this->method,
            $this->command,
            $this->active,
            $this->externalData,
            $this->started,
            $this->finished
        ];
    }

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Log", mappedBy="logs")
     */
    private $logs;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="JobTemplate", inversedBy="jobTemplate")
     * @ORM\JoinColumn(name="jobTemplate_id", referencedColumnName="id")
     */
    private $jobTemplate;

}