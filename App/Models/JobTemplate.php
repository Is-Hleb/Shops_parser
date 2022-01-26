<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs_templates", options={"collate"="utf8_general_ci"})
 */
class JobTemplate {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $class;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $method;

    /**
     * @return bool
     */
    public function getIsArrayInput(): bool {
        return $this->is_array_input;
    }

    /**
     * @param bool $is_array_input
     */
    public function setIsArrayInput(bool $is_array_input): void {
        $this->is_array_input = $is_array_input;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected bool $is_array_input;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected string $description;

    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="jobs")
     */
    private $jobs;

    public function addJob(Job $job) {
        $this->jobs[] = $job;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getToRead() {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'class' => $this->class,
          'method' => $this->method,
          'description' => $this->description
        ];
    }

}