<?php

namespace App\Entity;

use App\Repository\UserQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserQuestionRepository::class)]
class UserQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAsked = null;

    #[ORM\OneToOne(mappedBy: 'userQuestion', cascade: ['persist', 'remove'])]
    private ?AnswerToUserQuestion $answerToUserQuestion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getDateAsked(): ?\DateTimeInterface
    {
        return $this->dateAsked;
    }

    public function setDateAsked(?\DateTimeInterface $dateAsked): self
    {
        $this->dateAsked = $dateAsked;

        return $this;
    }

    public function getAnswerToUserQuestion(): ?AnswerToUserQuestion
    {
        return $this->answerToUserQuestion;
    }

    public function setAnswerToUserQuestion(?AnswerToUserQuestion $answerToUserQuestion): self
    {
        // unset the owning side of the relation if necessary
        if ($answerToUserQuestion === null && $this->answerToUserQuestion !== null) {
            $this->answerToUserQuestion->setUserQuestion(null);
        }

        // set the owning side of the relation if necessary
        if ($answerToUserQuestion !== null && $answerToUserQuestion->getUserQuestion() !== $this) {
            $answerToUserQuestion->setUserQuestion($this);
        }

        $this->answerToUserQuestion = $answerToUserQuestion;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

  
}
