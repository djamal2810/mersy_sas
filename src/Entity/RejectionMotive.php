<?php

namespace App\Entity;

use App\Repository\RejectionMotiveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RejectionMotiveRepository::class)]
class RejectionMotive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motive = null;

    #[ORM\OneToOne(inversedBy: 'rejectionMotive', cascade: ['persist', 'remove'])]
    private ?LegalCase $legalCase = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotive(): ?string
    {
        return $this->motive;
    }

    public function setMotive(?string $motive): self
    {
        $this->motive = $motive;

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
}
