<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logs", options={"collate"="utf8_general_ci"})
 */
class Log
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $type;

    /**
     * @ORM\Column(type="text")
     */
    protected string $value;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="logs")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $job;

    public function __construct(Job $job, string $value, string $type = 'error') {
        $this->value = $value;
        $this->type = $type;
        $this->job = $job;
        global $entityManager;
        $entityManager->persist($this);
    }

    public function returnToRead() : array {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'type' => $this->type
        ];
    }

}