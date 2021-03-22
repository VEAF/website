<?php

namespace App\Entity\Recruitment;

use App\Entity\User;
use App\Repository\Recruitment\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="recruitment_event")
 */
class Event
{
    const TYPE_TO_APPLY = 1;
    const TYPE_PRESENTATION = 2;
    const TYPE_PROMOTE = 3;
    const TYPE_ACTIVITY = 4;
    const TYPE_GUEST = 5;

    const TYPES = [
        self::TYPE_TO_APPLY => 'candidature',
        self::TYPE_PRESENTATION => 'presentation',
        self::TYPE_PROMOTE => 'promotion',
        self::TYPE_ACTIVITY => 'activité',
        self::TYPE_GUEST => 'invité',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $updatedAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $eventAt = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recruitmentEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $validator;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $ackAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getEventAt(): ?\DateTimeInterface
    {
        return $this->eventAt;
    }

    public function setEventAt(\DateTimeInterface $eventAt): self
    {
        $this->eventAt = $eventAt;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function getValidator(): ?User
    {
        return $this->validator;
    }

    public function setValidator(?User $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    public function getAckAt(): ?\DateTimeInterface
    {
        return $this->ackAt;
    }

    public function setAckAt(?\DateTimeInterface $ackAt): self
    {
        $this->ackAt = $ackAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function typeAsString(): string
    {
        if (isset(self::TYPES[$this->type])) {
            return self::TYPES[$this->type];
        }

        return 'inconnu';
    }
}
