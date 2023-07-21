<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abstract = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    private ?UserActivity $userActivity = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\OneToOne(mappedBy: 'announcement', cascade: ['persist', 'remove'])]
    private ?AnnouncementPoster $announcementPoster = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setAbstract(?string $abstract): self
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUserActivity(): ?UserActivity
    {
        return $this->userActivity;
    }

    public function setUserActivity(?UserActivity $userActivity): self
    {
        $this->userActivity = $userActivity;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

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

    public function getAnnouncementPoster(): ?AnnouncementPoster
    {
        return $this->announcementPoster;
    }

    public function setAnnouncementPoster(?AnnouncementPoster $announcementPoster): self
    {
        // unset the owning side of the relation if necessary
        if ($announcementPoster === null && $this->announcementPoster !== null) {
            $this->announcementPoster->setAnnouncement(null);
        }

        // set the owning side of the relation if necessary
        if ($announcementPoster !== null && $announcementPoster->getAnnouncement() !== $this) {
            $announcementPoster->setAnnouncement($this);
        }

        $this->announcementPoster = $announcementPoster;

        return $this;
    }
}
