<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $service = null;

    #[ORM\ManyToOne(inversedBy: 'assignedByMeetings')]
    private ?UserActivity $assignedBy = null;

    #[ORM\ManyToOne(inversedBy: 'assignedToMeetings')]
    private ?UserActivity $assignedTo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $assignmentDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getAssignedBy(): ?UserActivity
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?UserActivity $assignedBy): static
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    public function getAssignedTo(): ?UserActivity
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?UserActivity $assignedTo): static
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getAssignmentDate(): ?\DateTimeInterface
    {
        return $this->assignmentDate;
    }

    public function setAssignmentDate(?\DateTimeInterface $assignmentDate): static
    {
        $this->assignmentDate = $assignmentDate;

        return $this;
    }
  
}
