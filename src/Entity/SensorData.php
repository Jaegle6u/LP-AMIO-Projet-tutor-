<?php

namespace App\Entity;

use App\Repository\SensorDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorDataRepository::class)
 */
class SensorData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $sensor;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $value1;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $value2;

    /**
     * @ORM\Column(type="datetime", nullable=true,options={"default":"CURRENT_TIMESTAMP"})
     */
    private $reading_time;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $value3;

 

    public function __construct()
    {
        $this->reading_time = new \DateTime();
        
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?string
    {
        return $this->sensor;
    }

    public function setSensor(string $sensor): self
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getValue1(): ?string
    {
        return $this->value1;
    }

    public function setValue1(?string $value1): self
    {
        $this->value1 = $value1;

        return $this;
    }

    public function getValue2(): ?string
    {
        return $this->value2;
    }

    public function setValue2(?string $value2): self
    {
        $this->value2 = $value2;

        return $this;
    }
    public function readingTime_ToString()
    {
        return $this->reading_time->format('d/m/Y à H:i');
        
    }
    public function getReadingTime(): ?\DateTimeInterface
    {
        return $this->reading_time;
    }

    public function setReadingTime(?\DateTimeInterface $reading_time): self
    {
        $this->reading_time = $reading_time;

        return $this;
    }

    public function getValue3(): ?string
    {
        return $this->value3;
    }

    public function setValue3(?string $value3): self
    {
        $this->value3 = $value3;

        return $this;
    }

   
}
