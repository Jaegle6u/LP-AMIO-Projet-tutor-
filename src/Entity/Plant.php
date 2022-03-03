<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlantRepository::class)
 */
class Plant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Serre::class, inversedBy="plants")
     */
    private $serre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $espece;

    /**
     * @ORM\OneToMany(targetEntity=Plante::class, mappedBy="plant",orphanRemoval=true)
     */
    private $plantes;

    /**
     * @ORM\OneToOne(targetEntity=Arrosage::class, mappedBy="plant", cascade={"persist", "remove"})
     */
    private $arrosage;

    /**
     * @ORM\OneToMany(targetEntity=Rapport::class, mappedBy="plant",orphanRemoval=true)
     */
    private $rapports;

 

   

    public function __construct()
    {
        $this->plantes = new ArrayCollection();
        $this->rapports = new ArrayCollection();
        $this->sensorData = new ArrayCollection();
        
    }
    public function __toString()
    {
        $nom = "".$this->serre->getNom()." | ".$this->espece;
        return $nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerre(): ?Serre
    {
        return $this->serre;
    }

    public function setSerre(?Serre $serre): self
    {
        $this->serre = $serre;

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

    public function getEspece(): ?string
    {
        return $this->espece;
    }

    public function setEspece(string $espece): self
    {
        $this->espece = $espece;

        return $this;
    }

    /**
     * @return Collection|Plante[]
     */
    public function getPlantes(): Collection
    {
        return $this->plantes;
    }

    public function addPlante(Plante $plante): self
    {
        if (!$this->plantes->contains($plante)) {
            $this->plantes[] = $plante;
            $plante->setPlant($this);
        }

        return $this;
    }

    public function removePlante(Plante $plante): self
    {
        if ($this->plantes->removeElement($plante)) {
            // set the owning side to null (unless already changed)
            if ($plante->getPlant() === $this) {
                $plante->setPlant(null);
            }
        }

        return $this;
    }

    public function getArrosage(): ?Arrosage
    {
        return $this->arrosage;
    }

    public function setArrosage(?Arrosage $arrosage): self
    {
        // unset the owning side of the relation if necessary
        if ($arrosage === null && $this->arrosage !== null) {
            $this->arrosage->setPlant(null);
        }

        // set the owning side of the relation if necessary
        if ($arrosage !== null && $arrosage->getPlant() !== $this) {
            $arrosage->setPlant($this);
        }

        $this->arrosage = $arrosage;

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
            $rapport->setPlant($this);
        }

        return $this;
    }

    public function removeRapport(Rapport $rapport): self
    {
        if ($this->rapports->removeElement($rapport)) {
            // set the owning side to null (unless already changed)
            if ($rapport->getPlant() === $this) {
                $rapport->setPlant(null);
            }
        }

        return $this;
    }

    

  

   
}
