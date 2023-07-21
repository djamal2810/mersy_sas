<?php

namespace App\Entity;

use App\Repository\UserActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserActivityRepository::class)]
class UserActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'associatedUserActivity', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToOne(inversedBy: 'userActivity', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'userActivity', targetEntity: Announcement::class)]
    private Collection $announcements;

    #[ORM\OneToMany(mappedBy: 'answeredBy', targetEntity: AnswerToUserQuestion::class)]
    private Collection $answerToUserQuestions;

    #[ORM\OneToMany(mappedBy: 'assignedBy', targetEntity: Meeting::class)]
    private Collection $assignedByMeetings;

    #[ORM\OneToMany(mappedBy: 'assignedTo', targetEntity: Meeting::class)]
    private Collection $assignedToMeetings;


    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->userQuestions = new ArrayCollection();
        $this->answerToUserQuestions = new ArrayCollection();
        $this->assignedByMeetings = new ArrayCollection();
        $this->assignedToMeetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setAssociatedUserActivity($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAssociatedUserActivity() === $this) {
                $article->setAssociatedUserActivity(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->setUserActivity($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getUserActivity() === $this) {
                $announcement->setUserActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnswerToUserQuestion>
     */
    public function getAnswerToUserQuestions(): Collection
    {
        return $this->answerToUserQuestions;
    }

    public function addAnswerToUserQuestion(AnswerToUserQuestion $answerToUserQuestion): self
    {
        if (!$this->answerToUserQuestions->contains($answerToUserQuestion)) {
            $this->answerToUserQuestions->add($answerToUserQuestion);
            $answerToUserQuestion->setAnsweredBy($this);
        }

        return $this;
    }

    public function removeAnswerToUserQuestion(AnswerToUserQuestion $answerToUserQuestion): self
    {
        if ($this->answerToUserQuestions->removeElement($answerToUserQuestion)) {
            // set the owning side to null (unless already changed)
            if ($answerToUserQuestion->getAnsweredBy() === $this) {
                $answerToUserQuestion->setAnsweredBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getAssignedByMeetings(): Collection
    {
        return $this->assignedByMeetings;
    }

    public function addAssignedByMeeting(Meeting $assignedByMeeting): static
    {
        if (!$this->assignedByMeetings->contains($assignedByMeeting)) {
            $this->assignedByMeetings->add($assignedByMeeting);
            $assignedByMeeting->setAssignedBy($this);
        }

        return $this;
    }

    public function removeAssignedByMeeting(Meeting $assignedByMeeting): static
    {
        if ($this->assignedByMeetings->removeElement($assignedByMeeting)) {
            // set the owning side to null (unless already changed)
            if ($assignedByMeeting->getAssignedBy() === $this) {
                $assignedByMeeting->setAssignedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getAssignedToMeetings(): Collection
    {
        return $this->assignedToMeetings;
    }

    public function addAssignedToMeeting(Meeting $assignedToMeeting): static
    {
        if (!$this->assignedToMeetings->contains($assignedToMeeting)) {
            $this->assignedToMeetings->add($assignedToMeeting);
            $assignedToMeeting->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedToMeeting(Meeting $assignedToMeeting): static
    {
        if ($this->assignedToMeetings->removeElement($assignedToMeeting)) {
            // set the owning side to null (unless already changed)
            if ($assignedToMeeting->getAssignedTo() === $this) {
                $assignedToMeeting->setAssignedTo(null);
            }
        }

        return $this;
    }

}
