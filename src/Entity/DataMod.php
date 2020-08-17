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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $s3phmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $liters;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $workingtime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pamoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pamax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pbmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pbmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pcmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pcmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qamoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qamax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qbmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qbmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qcmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qcmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $samax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sbmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $scmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cosamin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cosamn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cosbmin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cosbmn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coscmin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coscmn;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pmax3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pmoy3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qmax3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qmoy3ph;

    /**
     * @ORM\Column(type="float")
     */
    private $cosmin3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cosmn3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vamin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vamax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vbmin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vbmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vcmin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vcmax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kwha;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kwhb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $kwhc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $era;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $erb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $erc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $thdia;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $thdib;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $thdic;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $thdi3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dmoya;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dmoyb;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dmoyc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dmoy3ph;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $idmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $iomoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vdmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vomoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fdimoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fdvmoy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $iddmoy;

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

    public function getS3phmax(): ?float
    {
        return $this->s3phmax;
    }

    public function setS3phmax(?float $s3phmax): self
    {
        $this->s3phmax = $s3phmax;

        return $this;
    }

    public function getLiters(): ?float
    {
        return $this->liters;
    }

    public function setLiters(?float $liters): self
    {
        $this->liters = $liters;

        return $this;
    }

    public function getWorkingtime(): ?string
    {
        return $this->workingtime;
    }

    public function setWorkingtime(?string $workingtime): self
    {
        $this->workingtime = $workingtime;

        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(?float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPamoy(): ?float
    {
        return $this->pamoy;
    }

    public function setPamoy(?float $pamoy): self
    {
        $this->pamoy = $pamoy;

        return $this;
    }

    public function getPamax(): ?float
    {
        return $this->pamax;
    }

    public function setPamax(?float $pamax): self
    {
        $this->pamax = $pamax;

        return $this;
    }

    public function getPbmoy(): ?float
    {
        return $this->pbmoy;
    }

    public function setPbmoy(?float $pbmoy): self
    {
        $this->pbmoy = $pbmoy;

        return $this;
    }

    public function getPbmax(): ?float
    {
        return $this->pbmax;
    }

    public function setPbmax(?float $pbmax): self
    {
        $this->pbmax = $pbmax;

        return $this;
    }

    public function getPcmoy(): ?float
    {
        return $this->pcmoy;
    }

    public function setPcmoy(?float $pcmoy): self
    {
        $this->pcmoy = $pcmoy;

        return $this;
    }

    public function getPcmax(): ?float
    {
        return $this->pcmax;
    }

    public function setPcmax(?float $pcmax): self
    {
        $this->pcmax = $pcmax;

        return $this;
    }

    public function getQamoy(): ?float
    {
        return $this->qamoy;
    }

    public function setQamoy(?float $qamoy): self
    {
        $this->qamoy = $qamoy;

        return $this;
    }

    public function getQamax(): ?float
    {
        return $this->qamax;
    }

    public function setQamax(?float $qamax): self
    {
        $this->qamax = $qamax;

        return $this;
    }

    public function getQbmoy(): ?float
    {
        return $this->qbmoy;
    }

    public function setQbmoy(?float $qbmoy): self
    {
        $this->qbmoy = $qbmoy;

        return $this;
    }

    public function getQbmax(): ?float
    {
        return $this->qbmax;
    }

    public function setQbmax(?float $qbmax): self
    {
        $this->qbmax = $qbmax;

        return $this;
    }

    public function getQcmoy(): ?float
    {
        return $this->qcmoy;
    }

    public function setQcmoy(?float $qcmoy): self
    {
        $this->qcmoy = $qcmoy;

        return $this;
    }

    public function getQcmax(): ?float
    {
        return $this->qcmax;
    }

    public function setQcmax(?float $qcmax): self
    {
        $this->qcmax = $qcmax;

        return $this;
    }

    public function getSamax(): ?float
    {
        return $this->samax;
    }

    public function setSamax(?float $samax): self
    {
        $this->samax = $samax;

        return $this;
    }

    public function getSbmax(): ?float
    {
        return $this->sbmax;
    }

    public function setSbmax(?float $sbmax): self
    {
        $this->sbmax = $sbmax;

        return $this;
    }

    public function getScmax(): ?float
    {
        return $this->scmax;
    }

    public function setScmax(?float $scmax): self
    {
        $this->scmax = $scmax;

        return $this;
    }

    public function getCosamin(): ?float
    {
        return $this->cosamin;
    }

    public function setCosamin(?float $cosamin): self
    {
        $this->cosamin = $cosamin;

        return $this;
    }

    public function getCosamn(): ?float
    {
        return $this->cosamn;
    }

    public function setCosamn(?float $cosamn): self
    {
        $this->cosamn = $cosamn;

        return $this;
    }

    public function getCosbmin(): ?float
    {
        return $this->cosbmin;
    }

    public function setCosbmin(?float $cosbmin): self
    {
        $this->cosbmin = $cosbmin;

        return $this;
    }

    public function getCosbmn(): ?float
    {
        return $this->cosbmn;
    }

    public function setCosbmn(?float $cosbmn): self
    {
        $this->cosbmn = $cosbmn;

        return $this;
    }

    public function getCoscmin(): ?float
    {
        return $this->coscmin;
    }

    public function setCoscmin(?float $coscmin): self
    {
        $this->coscmin = $coscmin;

        return $this;
    }

    public function getCoscmn(): ?float
    {
        return $this->coscmn;
    }

    public function setCoscmn(?float $coscmn): self
    {
        $this->coscmn = $coscmn;

        return $this;
    }

    public function getPmax3ph(): ?float
    {
        return $this->pmax3ph;
    }

    public function setPmax3ph(?float $pmax3ph): self
    {
        $this->pmax3ph = $pmax3ph;

        return $this;
    }

    public function getPmoy3ph(): ?float
    {
        return $this->pmoy3ph;
    }

    public function setPmoy3ph(?float $pmoy3ph): self
    {
        $this->pmoy3ph = $pmoy3ph;

        return $this;
    }

    public function getQmax3ph(): ?float
    {
        return $this->qmax3ph;
    }

    public function setQmax3ph(?float $qmax3ph): self
    {
        $this->qmax3ph = $qmax3ph;

        return $this;
    }

    public function getQmoy3ph(): ?float
    {
        return $this->qmoy3ph;
    }

    public function setQmoy3ph(?float $qmoy3ph): self
    {
        $this->qmoy3ph = $qmoy3ph;

        return $this;
    }

    public function getCosmin3ph(): ?float
    {
        return $this->cosmin3ph;
    }

    public function setCosmin3ph(float $cosmin3ph): self
    {
        $this->cosmin3ph = $cosmin3ph;

        return $this;
    }

    public function getCosmn3ph(): ?float
    {
        return $this->cosmn3ph;
    }

    public function setCosmn3ph(?float $cosmn3ph): self
    {
        $this->cosmn3ph = $cosmn3ph;

        return $this;
    }

    public function getVamin(): ?float
    {
        return $this->vamin;
    }

    public function setVamin(?float $vamin): self
    {
        $this->vamin = $vamin;

        return $this;
    }

    public function getVamax(): ?float
    {
        return $this->vamax;
    }

    public function setVamax(?float $vamax): self
    {
        $this->vamax = $vamax;

        return $this;
    }

    public function getVbmin(): ?float
    {
        return $this->vbmin;
    }

    public function setVbmin(?float $vbmin): self
    {
        $this->vbmin = $vbmin;

        return $this;
    }

    public function getVbmax(): ?float
    {
        return $this->vbmax;
    }

    public function setVbmax(?float $vbmax): self
    {
        $this->vbmax = $vbmax;

        return $this;
    }

    public function getVcmin(): ?float
    {
        return $this->vcmin;
    }

    public function setVcmin(?float $vcmin): self
    {
        $this->vcmin = $vcmin;

        return $this;
    }

    public function getVcmax(): ?float
    {
        return $this->vcmax;
    }

    public function setVcmax(?float $vcmax): self
    {
        $this->vcmax = $vcmax;

        return $this;
    }

    public function getKwha(): ?float
    {
        return $this->kwha;
    }

    public function setKwha(?float $kwha): self
    {
        $this->kwha = $kwha;

        return $this;
    }

    public function getKwhb(): ?float
    {
        return $this->kwhb;
    }

    public function setKwhb(?float $kwhb): self
    {
        $this->kwhb = $kwhb;

        return $this;
    }

    public function getKwhc(): ?float
    {
        return $this->kwhc;
    }

    public function setKwhc(?float $kwhc): self
    {
        $this->kwhc = $kwhc;

        return $this;
    }

    public function getEra(): ?float
    {
        return $this->era;
    }

    public function setEra(?float $era): self
    {
        $this->era = $era;

        return $this;
    }

    public function getErb(): ?float
    {
        return $this->erb;
    }

    public function setErb(?float $erb): self
    {
        $this->erb = $erb;

        return $this;
    }

    public function getErc(): ?float
    {
        return $this->erc;
    }

    public function setErc(?float $erc): self
    {
        $this->erc = $erc;

        return $this;
    }

    public function getThdia(): ?float
    {
        return $this->thdia;
    }

    public function setThdia(?float $thdia): self
    {
        $this->thdia = $thdia;

        return $this;
    }

    public function getThdib(): ?float
    {
        return $this->thdib;
    }

    public function setThdib(?float $thdib): self
    {
        $this->thdib = $thdib;

        return $this;
    }

    public function getThdic(): ?float
    {
        return $this->thdic;
    }

    public function setThdic(?float $thdic): self
    {
        $this->thdic = $thdic;

        return $this;
    }

    public function getThdi3ph(): ?float
    {
        return $this->thdi3ph;
    }

    public function setThdi3ph(?float $thdi3ph): self
    {
        $this->thdi3ph = $thdi3ph;

        return $this;
    }

    public function getDmoya(): ?float
    {
        return $this->dmoya;
    }

    public function setDmoya(?float $dmoya): self
    {
        $this->dmoya = $dmoya;

        return $this;
    }

    public function getDmoyb(): ?float
    {
        return $this->dmoyb;
    }

    public function setDmoyb(?float $dmoyb): self
    {
        $this->dmoyb = $dmoyb;

        return $this;
    }

    public function getDmoyc(): ?float
    {
        return $this->dmoyc;
    }

    public function setDmoyc(?float $dmoyc): self
    {
        $this->dmoyc = $dmoyc;

        return $this;
    }

    public function getDmoy3ph(): ?float
    {
        return $this->dmoy3ph;
    }

    public function setDmoy3ph(?float $dmoy3ph): self
    {
        $this->dmoy3ph = $dmoy3ph;

        return $this;
    }

    public function getIdmoy(): ?float
    {
        return $this->idmoy;
    }

    public function setIdmoy(?float $idmoy): self
    {
        $this->idmoy = $idmoy;

        return $this;
    }

    public function getIomoy(): ?float
    {
        return $this->iomoy;
    }

    public function setIomoy(?float $iomoy): self
    {
        $this->iomoy = $iomoy;

        return $this;
    }

    public function getVdmoy(): ?float
    {
        return $this->vdmoy;
    }

    public function setVdmoy(?float $vdmoy): self
    {
        $this->vdmoy = $vdmoy;

        return $this;
    }

    public function getVomoy(): ?float
    {
        return $this->vomoy;
    }

    public function setVomoy(?float $vomoy): self
    {
        $this->vomoy = $vomoy;

        return $this;
    }

    public function getFdimoy(): ?float
    {
        return $this->fdimoy;
    }

    public function setFdimoy(?float $fdimoy): self
    {
        $this->fdimoy = $fdimoy;

        return $this;
    }

    public function getFdvmoy(): ?float
    {
        return $this->fdvmoy;
    }

    public function setFdvmoy(?float $fdvmoy): self
    {
        $this->fdvmoy = $fdvmoy;

        return $this;
    }

    public function getIddmoy(): ?float
    {
        return $this->iddmoy;
    }

    public function setIddmoy(?float $iddmoy): self
    {
        $this->iddmoy = $iddmoy;

        return $this;
    }
}
