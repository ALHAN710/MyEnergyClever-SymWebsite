<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApproFuelRepository")
 */
class ApproFuel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\Date()
     * @Assert\LessThanOrEqual("today UTC+1")
     */
    private $addAt;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SmartMod", inversedBy="approFuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $smartMod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddAt(): ?\DateTimeInterface
    {
        return $this->addAt;
    }

    public function setAddAt(\DateTimeInterface $addAt): self
    {
        $this->addAt = $addAt;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSmartMod(): ?SmartMod
    {
        return $this->smartMod;
    }

    public function setSmartMod(?SmartMod $smartMod): self
    {
        $this->smartMod = $smartMod;

        return $this;
    }
}
