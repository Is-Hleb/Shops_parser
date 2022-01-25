<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="settings")
 */
class Setting {

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
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $value;

    /**
     * @ORM\Column(type="string", nullable="false")
     */
    protected string $collection;

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
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCollection(): string {
        return $this->collection;
    }

    /**
     * @param string $collection
     */
    public function setCollection(string $collection): void {
        $this->collection = $collection;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }


}