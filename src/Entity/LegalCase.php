<?php

namespace App\Entity;

use App\Repository\LegalCaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LegalCaseRepository::class)]
class LegalCase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $referenceNo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $casePresentation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\ManyToOne(inversedBy: 'createdLegalCases')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'legalCaseAssignments')]
    private ?User $assignedBy = null;

    #[ORM\ManyToOne(inversedBy: 'assignedLegalCases')]
    private ?User $assignedTo = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'legalCase', targetEntity: LegalCaseDocument::class, cascade: ['persist', 'remove'])]
    private Collection $legalCaseDocuments;

    #[ORM\OneToOne(mappedBy: 'legalCase', cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $expectations = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $assignmentDate = null;

    #[ORM\OneToOne(mappedBy: 'legalCase', cascade: ['persist', 'remove'])]
    private ?IncompleteMessage $incompleteMessage = null;

    #[ORM\OneToOne(mappedBy: 'legalCase', cascade: ['persist', 'remove'])]
    private ?RejectionMotive $rejectionMotive = null;

    #[ORM\ManyToOne(inversedBy: 'legalCases')]
    private ?LegalCaseCategory $category = null;

    #[ORM\OneToOne(mappedBy: 'legalCase', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    #[ORM\OneToOne(mappedBy: 'legalCase', cascade: ['persist', 'remove'])]
    private ?FinalReport $finalReport = null;

    public function __construct()
    {
        $this->legalCaseDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceNo(): ?string
    {
        return $this->referenceNo;
    }

    public function setReferenceNo(?string $referenceNo): self
    {
        $this->referenceNo = $referenceNo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCasePresentation(): ?string
    {
        return $this->casePresentation;
    }

    public function setCasePresentation(?string $casePresentation): self
    {
        $this->casePresentation = $casePresentation;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getAssignedBy(): ?User
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?User $assignedBy): self
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, LegalCaseDocument>
     */
    public function getLegalCaseDocuments(): Collection
    {
        return $this->legalCaseDocuments;
    }

    public function addLegalCaseDocument(LegalCaseDocument $legalCaseDocument): self
    {
        if (!$this->legalCaseDocuments->contains($legalCaseDocument)) {
            $this->legalCaseDocuments->add($legalCaseDocument);
            $legalCaseDocument->setLegalCase($this);
        }

        return $this;
    }

    public function removeLegalCaseDocument(LegalCaseDocument $legalCaseDocument): self
    {
        if ($this->legalCaseDocuments->removeElement($legalCaseDocument)) {
            // set the owning side to null (unless already changed)
            if ($legalCaseDocument->getLegalCase() === $this) {
                $legalCaseDocument->setLegalCase(null);
            }
        }

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        // unset the owning side of the relation if necessary
        if ($contact === null && $this->contact !== null) {
            $this->contact->setLegalCase(null);
        }

        // set the owning side of the relation if necessary
        if ($contact !== null && $contact->getLegalCase() !== $this) {
            $contact->setLegalCase($this);
        }

        $this->contact = $contact;

        return $this;
    }

    public function getExpectations(): ?string
    {
        return $this->expectations;
    }

    public function setExpectations(?string $expectations): self
    {
        $this->expectations = $expectations;

        return $this;
    }

    public function getAssignmentDate(): ?\DateTimeInterface
    {
        return $this->assignmentDate;
    }

    public function setAssignmentDate(?\DateTimeInterface $assignmentDate): self
    {
        $this->assignmentDate = $assignmentDate;

        return $this;
    }

    public function getIncompleteMessage(): ?IncompleteMessage
    {
        return $this->incompleteMessage;
    }

    public function setIncompleteMessage(?IncompleteMessage $incompleteMessage): self
    {
        // unset the owning side of the relation if necessary
        if ($incompleteMessage === null && $this->incompleteMessage !== null) {
            $this->incompleteMessage->setLegalCase(null);
        }

        // set the owning side of the relation if necessary
        if ($incompleteMessage !== null && $incompleteMessage->getLegalCase() !== $this) {
            $incompleteMessage->setLegalCase($this);
        }

        $this->incompleteMessage = $incompleteMessage;

        return $this;
    }

    public function getRejectionMotive(): ?RejectionMotive
    {
        return $this->rejectionMotive;
    }

    public function setRejectionMotive(?RejectionMotive $rejectionMotive): self
    {
        // unset the owning side of the relation if necessary
        if ($rejectionMotive === null && $this->rejectionMotive !== null) {
            $this->rejectionMotive->setLegalCase(null);
        }

        // set the owning side of the relation if necessary
        if ($rejectionMotive !== null && $rejectionMotive->getLegalCase() !== $this) {
            $rejectionMotive->setLegalCase($this);
        }

        $this->rejectionMotive = $rejectionMotive;

        return $this;
    }

    public function getCategory(): ?LegalCaseCategory
    {
        return $this->category;
    }

    public function setCategory(?LegalCaseCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        // unset the owning side of the relation if necessary
        if ($payment === null && $this->payment !== null) {
            $this->payment->setLegalCase(null);
        }

        // set the owning side of the relation if necessary
        if ($payment !== null && $payment->getLegalCase() !== $this) {
            $payment->setLegalCase($this);
        }

        $this->payment = $payment;

        return $this;
    }

    public function getFinalReport(): ?FinalReport
    {
        return $this->finalReport;
    }

    public function setFinalReport(?FinalReport $finalReport): self
    {
        // unset the owning side of the relation if necessary
        if ($finalReport === null && $this->finalReport !== null) {
            $this->finalReport->setLegalCase(null);
        }

        // set the owning side of the relation if necessary
        if ($finalReport !== null && $finalReport->getLegalCase() !== $this) {
            $finalReport->setLegalCase($this);
        }

        $this->finalReport = $finalReport;

        return $this;
    }
}
