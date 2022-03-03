<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RapportRepository::class)
 */
class Rapport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Serre::class, inversedBy="rapports")
     */
    private $serre;

    /**
     * @ORM\ManyToOne(targetEntity=Plant::class, inversedBy="rapports")
     */
    private $plant;

    /**
     * @ORM\ManyToOne(targetEntity=Plante::class, inversedBy="rapports")
     */
    private $plante;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $objet;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $probleme;

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

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }

    public function getPlante(): ?Plante
    {
        return $this->plante;
    }

    public function setPlante(?Plante $plante): self
    {
        $this->plante = $plante;

        return $this;
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
    public function date_ToString()
    {
        return $this->date->format('d/m/Y');
        
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getProbleme(): ?bool
    {
        return $this->probleme;
    }

    public function setProbleme(bool $probleme): self
    {
        $this->probleme = $probleme;

        return $this;
    }

    public function getElement(): ?string
    {
        if(!empty($this->serre)){
            return("".$this->getSerre()->getNom());
        }
        elseif(!empty($this->plant)){
            return("".$this->getPlant()->getSerre()->getNom()." | ".$this->getPlant()->getEspece());
        }
        elseif(!empty($this->plante)){
            return("".$this->getPlante()->getPlant()->getSerre()->getNom()." | ".$this->getPlante()->getPlant()->getEspece()." | Plante ".$this->getPlante()->getNumeros());
        }
        else{
            return("Potanet");
        }
    }
}
