<?php

namespace App\Entity;

use App\Repository\OurPartnerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OurPartnerRepository::class)]
class OurPartner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'ourPartner', cascade: ['persist', 'remove'])]
    private ?OurPartnerLogo $ourPartnerLogo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOurPartnerLogo(): ?OurPartnerLogo
    {
        return $this->ourPartnerLogo;
    }

    public function setOurPartnerLogo(?OurPartnerLogo $ourPartnerLogo): self
    {
        // unset the owning side of the relation if necessary
        if ($ourPartnerLogo === null && $this->ourPartnerLogo !== null) {
            $this->ourPartnerLogo->setOurPartner(null);
        }

        // set the owning side of the relation if necessary
        if ($ourPartnerLogo !== null && $ourPartnerLogo->getOurPartner() !== $this) {
            $ourPartnerLogo->setOurPartner($this);
        }

        $this->ourPartnerLogo = $ourPartnerLogo;

        return $this;
    }
}
