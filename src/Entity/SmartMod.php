<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmartModRepository")
 */
class SmartMod
{
    //const MODTYPE = ['FUEL', 'GRID'];
    public static function getModtyp()
    {
        return ['FUEL', 'GRID'];
    }
    public static function getInstatyp()
    {
        return ['Monophasé', 'Triphasé'];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer l'identifiant unique du module")
     */
    private $moduleId;

   

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(callback="getInstatyp", message="Choisir un type d'installation valide")
     */
    private $installationType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="smartMods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(callback="getModtyp", message="Choisir un type valide")
     */
    private $modType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DataMod", mappedBy="smartMod", orphanRemoval=true)
     */
    private $dataMods;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez donner le nom de la zone couvert par le module")
     */
    private $modName;

    public function __construct()
    {
        $this->dataMods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModuleId(): ?string
    {
        return $this->moduleId;
    }

    public function setModuleId(string $moduleId): self
    {
        $this->moduleId = $moduleId;

        return $this;
    }

    public function getAssociatedSite(): ?string
    {
        return $this->associatedSite;
    }

    public function setAssociatedSite(string $associatedSite): self
    {
        $this->associatedSite = $associatedSite;

        return $this;
    }

    public function getInstallationType(): ?string
    {
        return $this->installationType;
    }

    public function setInstallationType(string $installationType): self
    {
        $this->installationType = $installationType;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getModType(): ?string
    {
        return $this->modType;
    }

    public function setModType(string $modType): self
    {
        $this->modType = $modType;

        return $this;
    }

    /**
     * @return Collection|DataMod[]
     */
    public function getDataMods(): Collection
    {
        return $this->dataMods;
    }

    public function addDataMod(DataMod $dataMod): self
    {
        if (!$this->dataMods->contains($dataMod)) {
            $this->dataMods[] = $dataMod;
            $dataMod->setSmartMod($this);
        }

        return $this;
    }

    public function removeDataMod(DataMod $dataMod): self
    {
        if ($this->dataMods->contains($dataMod)) {
            $this->dataMods->removeElement($dataMod);
            // set the owning side to null (unless already changed)
            if ($dataMod->getSmartMod() === $this) {
                $dataMod->setSmartMod(null);
            }
        }

        return $this;
    }

    public function getModName(): ?string
    {
        return $this->modName;
    }

    public function setModName(string $modName): self
    {
        $this->modName = $modName;

        return $this;
    }
}

 
     // @ORM\Column(type="string", length=255)
     
    //private $associatedSite;