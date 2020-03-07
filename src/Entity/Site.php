<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields={"name"},
 * message="Un autre Site possède déjà ce nom, merci de le modifier"
 * )
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le nom du Site")
     */
    private $name;
 
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User1", inversedBy="sites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SmartMod", mappedBy="site", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $smartMods;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug; 

    /**
     * Permet d'initialiser le slug !
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->name);
        }
    }

    public function __construct()
    {
        $this->smartMods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User1
    {
        return $this->user;
    }

    public function setUser(?User1 $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|SmartMod[]
     */
    public function getSmartMods(): Collection
    {
        return $this->smartMods;
    }

    public function addSmartMod(SmartMod $smartMod): self
    {
        if (!$this->smartMods->contains($smartMod)) {
            $this->smartMods[] = $smartMod;
            $smartMod->setSite($this);
        }

        return $this;
    }

    public function removeSmartMod(SmartMod $smartMod): self
    {
        if ($this->smartMods->contains($smartMod)) {
            $this->smartMods->removeElement($smartMod);
            // set the owning side to null (unless already changed)
            if ($smartMod->getSite() === $this) {
                $smartMod->setSite(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
