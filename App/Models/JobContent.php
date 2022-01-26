<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs_content", options={"collate"="utf8_general_ci"})
 */
class JobContent
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column (type="text", nullable=false)
     */
    protected string $content;

    /**
     * @ORM\Column (type="string")
     */
    protected string $type = 'json';

    /**
     * Many contents have one jobs. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="contents")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     */
    private $job;

    public function __construct(mixed $content, Job $job) {
        if(is_array($content)) {
            $this->type = 'json';
            $this->content = json_encode($content);
        } else {
            $this->type = 'string';
            $this->content = "$content";
        }
        $this->job = $job;
        global $entityManager;
        $entityManager->persist($this);
    }

    public function getContent(): string|array {
        if($this->type === 'json') {
            $this->content = json_decode($this->content, true) ?? [];
        }
        return $this->content;
    }

}