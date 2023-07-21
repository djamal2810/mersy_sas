<?php

namespace App\Entity;

use App\Repository\FinalReportSignatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinalReportSignatureRepository::class)]
class FinalReportSignature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $adminSignatureXCoord = null;

    #[ORM\Column(nullable: true)]
    private ?float $adminSignatureYCoord = null;

    #[ORM\Column(nullable: true)]
    private ?float $consultantSignatureXCoord = null;

    #[ORM\Column(nullable: true)]
    private ?float $consultantSignatureYCoord = null;

    #[ORM\OneToOne(inversedBy: 'finalReportSignature', cascade: ['persist', 'remove'])]
    private ?FinalReport $finalReport = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $signedDocumentDirectPath = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $signedDocumentFileName = null;

    #[ORM\Column(nullable: true)]
    private ?int $signaturePage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdminSignatureXCoord(): ?float
    {
        return $this->adminSignatureXCoord;
    }

    public function setAdminSignatureXCoord(?float $adminSignatureXCoord): self
    {
        $this->adminSignatureXCoord = $adminSignatureXCoord;

        return $this;
    }

    public function getAdminSignatureYCoord(): ?float
    {
        return $this->adminSignatureYCoord;
    }

    public function setAdminSignatureYCoord(?float $adminSignatureYCoord): self
    {
        $this->adminSignatureYCoord = $adminSignatureYCoord;

        return $this;
    }

    public function getConsultantSignatureXCoord(): ?float
    {
        return $this->consultantSignatureXCoord;
    }

    public function setConsultantSignatureXCoord(?float $consultantSignatureXCoord): self
    {
        $this->consultantSignatureXCoord = $consultantSignatureXCoord;

        return $this;
    }

    public function getConsultantSignatureYCoord(): ?float
    {
        return $this->consultantSignatureYCoord;
    }

    public function setConsultantSignatureYCoord(?float $consultantSignatureYCoord): self
    {
        $this->consultantSignatureYCoord = $consultantSignatureYCoord;

        return $this;
    }

    public function getFinalReport(): ?FinalReport
    {
        return $this->finalReport;
    }

    public function setFinalReport(?FinalReport $finalReport): self
    {
        $this->finalReport = $finalReport;

        return $this;
    }

    public function getSignedDocumentDirectPath(): ?string
    {
        return $this->signedDocumentDirectPath;
    }

    public function setSignedDocumentDirectPath(?string $signedDocumentDirectPath): self
    {
        $this->signedDocumentDirectPath = $signedDocumentDirectPath;

        return $this;
    }

    public function getSignedDocumentFileName(): ?string
    {
        return $this->signedDocumentFileName;
    }

    public function setSignedDocumentFileName(?string $signedDocumentFileName): self
    {
        $this->signedDocumentFileName = $signedDocumentFileName;

        return $this;
    }

    public function getSignaturePage(): ?int
    {
        return $this->signaturePage;
    }

    public function setSignaturePage(?int $signaturePage): self
    {
        $this->signaturePage = $signaturePage;

        return $this;
    }
}
