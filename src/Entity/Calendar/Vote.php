<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Table(name="event_vote")
 * @ORM\Entity(repositoryClass=VoteRepository::class)
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?bool $vote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getVote(): ?bool
    {
        return $this->vote;
    }

    public function setVote(?bool $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
