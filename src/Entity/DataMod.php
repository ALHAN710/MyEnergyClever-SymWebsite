<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataModRepository")
 */
class DataMod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="float")
     */
    private $sa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $s3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kWh;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kVarh;

    /**
     * @ORM\Column(type="float")
     */
    private $va;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SmartMod", inversedBy="dataMods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $smartMod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getSa(): ?float
    {
        return $this->sa;
    }

    public function setSa(float $sa): self
    {
        $this->sa = $sa;

        return $this;
    }

    public function getSb(): ?float
    {
        return $this->sb;
    }

    public function setSb(?float $sb): self
    {
        $this->sb = $sb;

        return $this;
    }

    public function getSc(): ?float
    {
        return $this->sc;
    }

    public function setSc(?float $sc): self
    {
        $this->sc = $sc;

        return $this;
    }

    public function getS3ph(): ?float
    {
        return $this->s3ph;
    }

    public function setS3ph(?float $s3ph): self
    {
        $this->s3ph = $s3ph;

        return $this;
    }

    public function getKWh(): ?float
    {
        return $this->kWh;
    }

    public function setKWh(?float $kWh): self
    {
        $this->kWh = $kWh;

        return $this;
    }

    public function getKVarh(): ?float
    {
        return $this->kVarh;
    }

    public function setKVarh(?float $kVarh): self
    {
        $this->kVarh = $kVarh;

        return $this;
    }

    public function getVa(): ?float
    {
        return $this->va;
    }

    public function setVa(float $va): self
    {
        $this->va = $va;

        return $this;
    }

    public function getVb(): ?float
    {
        return $this->vb;
    }

    public function setVb(?float $vb): self
    {
        $this->vb = $vb;

        return $this;
    }

    public function getVc(): ?float
    {
        return $this->vc;
    }

    public function setVc(?float $vc): self
    {
        $this->vc = $vc;

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
