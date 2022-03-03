<?php

namespace App\Entity;

use App\Repository\ArrosageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
/**
 * @ORM\Entity(repositoryClass=ArrosageRepository::class)
 */
class Arrosage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Plant::class, inversedBy="arrosage")
     * @MaxDepth(1)
     */
    private $plant;

    /**
     * @ORM\Column(type="float")
     */
    private $minTemperature;

    /**
     * @ORM\Column(type="float")
     */
    private $maxTemperature;

    /**
     * @ORM\Column(type="float")
     */
    private $minHumidite;

    /**
     * @ORM\Column(type="float")
     */
    private $maxHumidite;

    /**
     * @ORM\Column(type="float")
     */
    private $minHumiditeSol;

    /**
     * @ORM\Column(type="float")
     */
    private $maxHumiditeSol;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkTemperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkHumidite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkHumiditeSol;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif=false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }

    public function getMinTemperature(): ?float
    {
        return $this->minTemperature;
    }

    public function setMinTemperature(float $minTemperature): self
    {
        $this->minTemperature = $minTemperature;

        return $this;
    }

    public function getMaxTemperature(): ?float
    {
        return $this->maxTemperature;
    }

    public function setMaxTemperature(float $maxTemperature): self
    {
        $this->maxTemperature = $maxTemperature;

        return $this;
    }

    public function getMinHumidite(): ?float
    {
        return $this->minHumidite;
    }

    public function setMinHumidite(float $minHumidite): self
    {
        $this->minHumidite = $minHumidite;

        return $this;
    }

    public function getMaxHumidite(): ?float
    {
        return $this->maxHumidite;
    }

    public function setMaxHumidite(float $maxHumidite): self
    {
        $this->maxHumidite = $maxHumidite;

        return $this;
    }

    public function getMinHumiditeSol(): ?float
    {
        return $this->minHumiditeSol;
    }

    public function setMinHumiditeSol(float $minHumiditeSol): self
    {
        $this->minHumiditeSol = $minHumiditeSol;

        return $this;
    }

    public function getMaxHumiditeSol(): ?float
    {
        return $this->maxHumiditeSol;
    }

    public function setMaxHumiditeSol(float $maxHumiditeSol): self
    {
        $this->maxHumiditeSol = $maxHumiditeSol;

        return $this;
    }

    public function getCheckTemperature(): ?bool
    {
        return $this->checkTemperature;
    }

    public function setCheckTemperature(bool $checkTemperature): self
    {
        $this->checkTemperature = $checkTemperature;

        return $this;
    }

    public function getCheckHumidite(): ?bool
    {
        return $this->checkHumidite;
    }

    public function setCheckHumidite(bool $checkHumidite): self
    {
        $this->checkHumidite = $checkHumidite;

        return $this;
    }

    public function getCheckHumiditeSol(): ?bool
    {
        return $this->checkHumiditeSol;
    }

    public function setCheckHumiditeSol(bool $checkHumiditeSol): self
    {
        $this->checkHumiditeSol = $checkHumiditeSol;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}
