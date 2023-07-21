<?php

namespace App\Entity;

use App\Repository\IncompleteMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncompleteMessageRepository::class)]
class IncompleteMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\OneToOne(inversedBy: 'incompleteMessage', cascade: ['persist', 'remove'])]
    private ?LegalCase $legalCase = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
