<?php

namespace App\Entity;

use App\Repository\LegalCaseCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LegalCaseCategoryRepository::class)]
class LegalCaseCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75, nullable: true)]
    private ?string $category = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: LegalCase::class)]
    private Collection $legalCases;

    public function __construct()
    {
        $this->legalCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, LegalCase>
     */
    public function getLegalCases(): Collection
    {
        return $this->legalCases;
    }

    public function addLegalCase(LegalCase $legalCase): self
    {
        if (!$this->legalCases->contains($legalCase)) {
            $this->legalCases->add($legalCase);
            $legalCase->setCategory($this);
        }

        return $this;
    }

    public function removeLegalCase(LegalCase $legalCase): self
    {
        if ($this->legalCases->removeElement($legalCase)) {
            // set the owning side to null (unless already changed)
            if ($legalCase->getCategory() === $this) {
                $legalCase->setCategory(null);
            }
        }

        return $this;
    }
}
