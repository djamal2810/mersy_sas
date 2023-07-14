<?php

namespace App\Entity;

use App\Repository\AnswerToUserQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerToUserQuestionRepository::class)]
class AnswerToUserQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'answerToUserQuestion', cascade: ['persist', 'remove'])]
    private ?UserQuestion $userQuestion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAnswered = null;

    #[ORM\ManyToOne(inversedBy: 'answerToUserQuestions')]
    private ?UserActivity $answeredBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answer = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserQuestion(): ?UserQuestion
    {
        return $this->userQuestion;
    }

    public function setUserQuestion(?UserQuestion $userQuestion): self
    {
        $this->userQuestion = $userQuestion;

        return $this;
    }

    public function getDateAnswered(): ?\DateTimeInterface
    {
        return $this->dateAnswered;
    }

    public function setDateAnswered(?\DateTimeInterface $dateAnswered): self
    {
        $this->dateAnswered = $dateAnswered;

        return $this;
    }

    public function getAnsweredBy(): ?UserActivity
    {
        return $this->answeredBy;
    }

    public function setAnsweredBy(?UserActivity $answeredBy): self
    {
        $this->answeredBy = $answeredBy;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    
}
