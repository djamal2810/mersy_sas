<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserDetails $userDetails = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $status = [];

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: LegalCase::class)]
    private Collection $createdLegalCases;

    #[ORM\OneToMany(mappedBy: 'assignedBy', targetEntity: LegalCase::class)]
    private Collection $legalCaseAssignments;

    #[ORM\OneToMany(mappedBy: 'assignedTo', targetEntity: LegalCase::class)]
    private Collection $assignedLegalCases;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserActivity $userActivity = null;

    public function __construct()
    {
        $this->userActivities = new ArrayCollection();
        $this->createdLegalCases = new ArrayCollection();
        $this->legalCaseAssignments = new ArrayCollection();
        $this->assignedLegalCases = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
	
	
    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
	
    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): self
    {
        // unset the owning side of the relation if necessary
        if ($userDetails === null && $this->userDetails !== null) {
            $this->userDetails->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userDetails !== null && $userDetails->getUser() !== $this) {
            $userDetails->setUser($this);
        }

        $this->userDetails = $userDetails;

        return $this;
    }

    /**
     * @return Collection<int, LegalCase>
     */
    public function getCreatedLegalCases(): Collection
    {
        return $this->createdLegalCases;
    }

    public function addCreatedLegalCase(LegalCase $createdLegalCase): self
    {
        if (!$this->createdLegalCases->contains($createdLegalCase)) {
            $this->createdLegalCases->add($createdLegalCase);
            $createdLegalCase->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedLegalCase(LegalCase $createdLegalCase): self
    {
        if ($this->createdLegalCases->removeElement($createdLegalCase)) {
            // set the owning side to null (unless already changed)
            if ($createdLegalCase->getCreatedBy() === $this) {
                $createdLegalCase->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LegalCase>
     */
    public function getLegalCaseAssignments(): Collection
    {
        return $this->legalCaseAssignments;
    }

    public function addLegalCaseAssignment(LegalCase $legalCaseAssignment): self
    {
        if (!$this->legalCaseAssignments->contains($legalCaseAssignment)) {
            $this->legalCaseAssignments->add($legalCaseAssignment);
            $legalCaseAssignment->setAssignedBy($this);
        }

        return $this;
    }

    public function removeLegalCaseAssignment(LegalCase $legalCaseAssignment): self
    {
        if ($this->legalCaseAssignments->removeElement($legalCaseAssignment)) {
            // set the owning side to null (unless already changed)
            if ($legalCaseAssignment->getAssignedBy() === $this) {
                $legalCaseAssignment->setAssignedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LegalCase>
     */
    public function getAssignedLegalCases(): Collection
    {
        return $this->assignedLegalCases;
    }

    public function addAssignedLegalCase(LegalCase $assignedLegalCase): self
    {
        if (!$this->assignedLegalCases->contains($assignedLegalCase)) {
            $this->assignedLegalCases->add($assignedLegalCase);
            $assignedLegalCase->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedLegalCase(LegalCase $assignedLegalCase): self
    {
        if ($this->assignedLegalCases->removeElement($assignedLegalCase)) {
            // set the owning side to null (unless already changed)
            if ($assignedLegalCase->getAssignedTo() === $this) {
                $assignedLegalCase->setAssignedTo(null);
            }
        }

        return $this;
    }

    public function getUserActivity(): ?UserActivity
    {
        return $this->userActivity;
    }

    public function setUserActivity(?UserActivity $userActivity): self
    {
        // unset the owning side of the relation if necessary
        if ($userActivity === null && $this->userActivity !== null) {
            $this->userActivity->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userActivity !== null && $userActivity->getUser() !== $this) {
            $userActivity->setUser($this);
        }

        $this->userActivity = $userActivity;

        return $this;
    }

	public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }
	
}
