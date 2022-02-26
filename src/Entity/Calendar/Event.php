<?php

namespace App\Entity\Calendar;

use App\Entity\File;
use App\Entity\Module;
use App\Entity\Server;
use App\Entity\User;
use App\Repository\Calendar\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    const EVENT_TYPE_ATC = 6;

    const EVENTS = [
        self::EVENT_TYPE_TRAINING => 'Training',
        self::EVENT_TYPE_MISSION => 'Mission',
        self::EVENT_TYPE_OPEX => 'OPEX',
        self::EVENT_TYPE_MEETING => 'Meeting',
        self::EVENT_TYPE_MAINTENANCE => 'Maintenance',
        self::EVENT_TYPE_ATC => 'ATC / GCI',
    ];

    const EVENTS_COLORS = [
        self::EVENT_TYPE_TRAINING => '#27AE60',
        self::EVENT_TYPE_MISSION => '#F1C40F',
        self::EVENT_TYPE_OPEX => '#7D3C98',
        self::EVENT_TYPE_MEETING => '#2980B9',
        self::EVENT_TYPE_MAINTENANCE => '#E74C3C',
        self::EVENT_TYPE_ATC => '#EA9417',
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
     * @Assert\Expression(
     *     "this.getEndDate() > this.getStartDate()",
     *     message="La date de fin doit être supérieure à la date de début"
     * )
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

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="event")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=Choice::class, mappedBy="event", orphanRemoval=true)
     */
    private $choices;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $registration = false;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     */
    private ?Server $server = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $ato = false;

    /**
     * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="event", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     *
     * @var Flight[]
     */
    private $flights;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->choices = new ArrayCollection();
        $this->flights = new ArrayCollection();
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

    public function getRestrictionByIdAsString(int $restriction): string
    {
        if (isset(self::RESTRICTIONS[$restriction])) {
            return self::RESTRICTIONS[$restriction];
        }

        return 'inconnu';
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

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setEvent($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getEvent() === $this) {
                $notification->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Choice[]
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices[] = $choice;
            $choice->setEvent($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): self
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getEvent() === $this) {
                $choice->setEvent(null);
            }
        }

        return $this;
    }

    public function isRegistration(): bool
    {
        return $this->registration;
    }

    public function setRegistration(bool $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function __clone()
    {
        $this->title = 'Copie de '.$this->title;

        $modules = $this->modules;
        $this->modules = new ArrayCollection();
        $this->votes = new ArrayCollection();
        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getAto(): ?bool
    {
        return $this->ato;
    }

    public function setAto(bool $ato): self
    {
        $this->ato = $ato;

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getFlights(): Collection
    {
        return $this->flights;
    }

    public function addFlight(Flight $flight): self
    {
        if (!$this->flights->contains($flight)) {
            $this->flights[] = $flight;
            $flight->setEvent($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): self
    {
        if ($this->flights->removeElement($flight)) {
            // set the owning side to null (unless already changed)
            if ($flight->getEvent() === $this) {
                $flight->setEvent(null);
            }
        }

        return $this;
    }
}
