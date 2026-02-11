<?php

namespace App\Entity\Calendar;

use App\Entity\Module;
use App\Repository\Calendar\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    public const MISSION_UNDEFINED = 0;
    public const MISSION_CAP = 1;
    public const MISSION_CAS = 2;
    public const MISSION_SEAD = 3;
    public const MISSION_ESCORT = 4;
    public const MISSION_TRANSPORT = 5;
    public const MISSION_RECON = 6;
    public const MISSION_CSAR = 7;
    public const MISSION_TANKER = 8;
    public const MISSION_AWACS = 9;
    public const MISSION_FAC = 10;

    public const MISSIONS = [
        self::MISSION_UNDEFINED => 'non définie',
        self::MISSION_CAP => 'CAP',
        self::MISSION_CAS => 'CAS / Strike',
        self::MISSION_SEAD => 'SEAD',
        self::MISSION_ESCORT => 'Escorte',
        self::MISSION_TRANSPORT => 'Transport',
        self::MISSION_RECON => 'Reconnaissance',
        self::MISSION_CSAR => 'CSAR',
        self::MISSION_TANKER => 'Ravitailleur',
        self::MISSION_AWACS => 'AWACS',
        self::MISSION_FAC => 'FAC / JTAC',
    ];

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="flights")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event = null;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\NotBlank
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $mission = null;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class)
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Module $aircraft = null;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     *
     * @Assert\Positive()
     */
    private ?int $nbSlots = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $departureBase = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $returnBase = null;

    /**
     * @ORM\OneToMany(targetEntity=Slot::class, mappedBy="flight", orphanRemoval=true, cascade={"persist", "remove"})
     *
     * @var Slot[]
     */
    private $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMission(): ?int
    {
        return $this->mission;
    }

    public function setMission(?int $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getMissionAsString(): string
    {
        if (isset(self::MISSIONS[$this->mission])) {
            return self::MISSIONS[$this->mission];
        }

        return 'non définie';
    }

    public function getAircraft(): ?Module
    {
        return $this->aircraft;
    }

    public function setAircraft(?Module $aircraft): self
    {
        $this->aircraft = $aircraft;

        return $this;
    }

    public function getNbSlots(): ?int
    {
        return $this->nbSlots;
    }

    public function setNbSlots(int $nbSlots): self
    {
        $this->nbSlots = $nbSlots;

        return $this;
    }

    public function getDepartureBase(): ?string
    {
        return $this->departureBase;
    }

    public function setDepartureBase(?string $departureBase): self
    {
        $this->departureBase = $departureBase;

        return $this;
    }

    public function getReturnBase(): ?string
    {
        return $this->returnBase;
    }

    public function setReturnBase(?string $returnBase): self
    {
        $this->returnBase = $returnBase;

        return $this;
    }

    /**
     * @return Collection|Slot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setFlight($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getFlight() === $this) {
                $slot->setFlight(null);
            }
        }

        return $this;
    }
}
