<?php

namespace App\Entity\Calendar;

use App\Entity\File;
use App\Entity\Module;
use App\Entity\User;
use App\Repository\Calendar\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    const EVENT_TYPE_TRAINING = 1;
    const EVENT_TYPE_MISSION = 2;
    const EVENT_TYPE_OPEX = 3;
    const EVENT_TYPE_MEETING = 4;
    const EVENT_TYPE_MAINTENANCE = 5;

    const EVENTS = [
        self::EVENT_TYPE_TRAINING => 'Training',
        self::EVENT_TYPE_MISSION => 'Mission',
        self::EVENT_TYPE_OPEX => 'OPEX',
        self::EVENT_TYPE_MEETING => 'Meeting',
        self::EVENT_TYPE_MAINTENANCE => 'Maintenance',
    ];

    const EVENTS_COLORS = [
        self::EVENT_TYPE_TRAINING => '#27AE60',
        self::EVENT_TYPE_MISSION => '#F1C40F',
        self::EVENT_TYPE_OPEX => '#7D3C98',
        self::EVENT_TYPE_MEETING => '#2980B9',
        self::EVENT_TYPE_MAINTENANCE => '#E74C3C',
    ];

    const RESTRICTION_CADET = 1;
    const RESTRICTION_MEMBER = 2;

    const RESTRICTIONS = [
        self::RESTRICTION_CADET => 'Cadets',
        self::RESTRICTION_MEMBER => 'Membres',
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

    /**     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $simDcs = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $simBms = false;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class)
     */
    private ?Module $map;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=File::class)
     */
    private ?File $image = null;

    /**
     * User status restriction.
     *
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private array $restrictions;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @var ArrayCollection|Module[]
     * @ORM\ManyToMany(targetEntity=Module::class)
     */
    private $modules;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $deletedAt;

    /**
     * @var ArrayCollection|Vote[]
     * @ORM\OneToMany (targetEntity=Vote::class, mappedBy="event")
     */
    private $votes;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getSimDcs(): ?bool
    {
        return $this->simDcs;
    }

    public function setSimDcs(bool $simDcs): self
    {
        $this->simDcs = $simDcs;

        return $this;
    }

    public function getSimBms(): ?bool
    {
        return $this->simBms;
    }

    public function setSimBms(bool $simBms): self
    {
        $this->simBms = $simBms;

        return $this;
    }

    public function getMap(): ?Module
    {
        return $this->map;
    }

    public function setMap(?Module $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(?File $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRestrictions(): ?array
    {
        return $this->restrictions;
    }

    public function setRestrictions(array $restrictions): self
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function hasRestriction(int $restriction): bool
    {
        if (null === $this->restrictions) {
            return false;
        }

        return in_array($restriction, $this->restrictions);
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Module[]
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        $this->modules->removeElement($module);

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public static function getTypeByIdAsString(int $type): string
    {
        if (isset(self::EVENTS[$type])) {
            return self::EVENTS[$type];
        }

        return 'inconnu';
    }

    public function getTypeAsString(): string
    {
        return self::getTypeByIdAsString($this->type);
    }

    public function getTypeColor(): string
    {
        if (isset(self::EVENTS_COLORS[$this->type])) {
            return self::EVENTS_COLORS[$this->type];
        }

        return '#000000';
    }

    public function isFinished(): bool
    {
        return $this->endDate->getTimestamp() < time();
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotesByVote(?bool $voteFilter): Collection
    {
        $votes = new ArrayCollection();
        foreach ($this->votes as $vote) {
            if ($voteFilter === $vote->getVote()) {
                $votes[] = $vote;
            }
        }

        return $votes;
    }
}
