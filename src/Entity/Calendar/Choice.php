<?php

namespace App\Entity\Calendar;

use App\Entity\Module;
use App\Entity\User;
use App\Repository\Calendar\ChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="event_choice")
 * @ORM\Entity(repositoryClass=ChoiceRepository::class)
 */
class Choice
{
    const TASK_UNDEFINED = 0;
    const TASK_CAP = 1;
    const TASK_CAS = 2;
    const TASK_SEAD = 3;
    const TASK_ESCORT = 4;
    const TASK_TRANSPORT = 5;

    const TASKS = [
        self::TASK_UNDEFINED => 'non définie',
        self::TASK_CAP => 'CAP',
        self::TASK_CAS => 'CAS / Strike',
        self::TASK_SEAD => 'SEAD',
        self::TASK_ESCORT => 'Escorte',
        self::TASK_TRANSPORT => 'Transport',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="choices")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Module $module = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $task;

    /**
     * @ORM\Column(type="integer")
     */
    private int $priority = 1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getTask(): ?int
    {
        return $this->task;
    }

    public function setTask(?int $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTaskAsString(): string
    {
        if (isset(self::TASKS[$this->task])) {
            return self::TASKS[$this->task];
        }

        return 'non définie';
    }
}
