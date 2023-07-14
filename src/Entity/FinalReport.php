<?php

namespace App\Entity;

use App\Repository\FinalReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinalReportRepository::class)]
class FinalReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $legalAnalysis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $legalAdvice = null;

    #[ORM\OneToOne(inversedBy: 'finalReport', cascade: ['persist', 'remove'])]
    private ?LegalCase $legalCase = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $pdfDirectoryPath = null;

    #[ORM\Column(length: 70, nullable: true)]
    private ?string $pdfFileName = null;

    #[ORM\OneToOne(mappedBy: 'finalReport', cascade: ['persist', 'remove'])]
    private ?FinalReportSignature $finalReportSignature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getLegalAnalysis(): ?string
    {
        return $this->legalAnalysis;
    }

    public function setLegalAnalysis(?string $legalAnalysis): self
    {
        $this->legalAnalysis = $legalAnalysis;

        return $this;
    }

    public function getLegalAdvice(): ?string
    {
        return $this->legalAdvice;
    }

    public function setLegalAdvice(?string $legalAdvice): self
    {
        $this->legalAdvice = $legalAdvice;

        return $this;
    }

    public function getLegalCase(): ?LegalCase
    {
        return $this->legalCase;
    }

    public function setLegalCase(?LegalCase $legalCase): self
    {
        $this->legalCase = $legalCase;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPdfDirectoryPath(): ?string
    {
        return $this->pdfDirectoryPath;
    }

    public function setPdfDirectoryPath(?string $pdfDirectoryPath): self
    {
        $this->pdfDirectoryPath = $pdfDirectoryPath;

        return $this;
    }

    public function getPdfFileName(): ?string
    {
        return $this->pdfFileName;
    }

    public function setPdfFileName(?string $pdfFileName): self
    {
        $this->pdfFileName = $pdfFileName;

        return $this;
    }

    public function getFinalReportSignature(): ?FinalReportSignature
    {
        return $this->finalReportSignature;
    }

    public function setFinalReportSignature(?FinalReportSignature $finalReportSignature): self
    {
        // unset the owning side of the relation if necessary
        if ($finalReportSignature === null && $this->finalReportSignature !== null) {
            $this->finalReportSignature->setFinalReport(null);
        }

        // set the owning side of the relation if necessary
        if ($finalReportSignature !== null && $finalReportSignature->getFinalReport() !== $this) {
            $finalReportSignature->setFinalReport($this);
        }

        $this->finalReportSignature = $finalReportSignature;

        return $this;
    }
}
