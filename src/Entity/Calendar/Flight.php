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
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event = null;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $mission = null;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Module $aircraft = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Positive()
     */
    private ?int $nbSlots = null;

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

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(?string $mission): self
    {
        $this->mission = $mission;

        return $this;
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
