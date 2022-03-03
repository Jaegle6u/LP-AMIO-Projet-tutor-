<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanteRepository::class)
 */
class Plante
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Plant::class, inversedBy="plantes")
     */
    private $plant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Rapport::class, mappedBy="plante",orphanRemoval=true)
     */
    private $rapports;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeros;

    public function __construct()
    {
        $this->rapports = new ArrayCollection();
    }

    public function __toString()
    {
        $nom = "".$this->getPlant()->getSerre()->getNom()." | ".$this->plant->getEspece()." | Plante ".$this->numeros;
        return $nom;
    }

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
    public function date_ToString()
    {
        return $this->date->format('d/m/Y');
        
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Rapport[]
     */
    public function getRapports(): Collection
    {
        return $this->rapports;
    }

    public function addRapport(Rapport $rapport): self
    {
        if (!$this->rapports->contains($rapport)) {
            $this->rapports[] = $rapport;
            $rapport->setPlante($this);
        }

        return $this;
    }

    public function removeRapport(Rapport $rapport): self
    {
        if ($this->rapports->removeElement($rapport)) {
            // set the owning side to null (unless already changed)
            if ($rapport->getPlante() === $this) {
                $rapport->setPlante(null);
            }
        }

        return $this;
    }

    public function getNumeros(): ?int
    {
        return $this->numeros;
    }

    public function setNumeros(int $numeros): self
    {
        $this->numeros = $numeros;

        return $this;
    }
    
}
