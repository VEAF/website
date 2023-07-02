<?php

namespace App\Entity;

use App\Repository\VariantStatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated https://github.com/VEAF/website/issues/365
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="variant_idx", columns={"server_id", "variant_id", "player_id"}),
 * })
 * @ORM\Entity(repositoryClass=VariantStatRepository::class)
 */
class VariantStat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Server $server;

    /**
     * @ORM\ManyToOne(targetEntity=Variant::class, inversedBy="stats")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Variant $variant;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Player $player;

    /**
     * @ORM\Column(type="float")
     */
    private float $total = 0;

    /**
     * @ORM\Column(type="float")
     */
    private float $inAir = 0;

    /**
     * @ORM\OneToMany(targetEntity=WeaponStat::class, mappedBy="variantStat")
     */
    private $weapons;

    /**
     * @ORM\Column(type="integer")
     */
    private int $killsGroundUnitsTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $killsBuildingsTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $killsPlanesTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $killsHelicoptersTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $killsShipsTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $landingTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $takeoffTotal = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $lossesPilotDeath = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $lossesCrash = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $lossesEject = 0;

    public function __construct()
    {
        $this->weapons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getInAir(): ?float
    {
        return $this->inAir;
    }

    public function setInAir(float $inAir): self
    {
        $this->inAir = $inAir;

        return $this;
    }

    /**
     * @return Collection|WeaponStat[]
     */
    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    public function addWeapon(WeaponStat $weapon): self
    {
        if (!$this->weapons->contains($weapon)) {
            $this->weapons[] = $weapon;
            $weapon->setVariantStat($this);
        }

        return $this;
    }

    public function removeWeapon(WeaponStat $weapon): self
    {
        $this->weapons->removeElement($weapon);

        return $this;
    }

    public function getKillsGroundUnitsTotal(): ?int
    {
        return $this->killsGroundUnitsTotal;
    }

    public function setKillsGroundUnitsTotal(int $killsGroundUnitsTotal): self
    {
        $this->killsGroundUnitsTotal = $killsGroundUnitsTotal;

        return $this;
    }

    public function getKillsBuildingsTotal(): ?int
    {
        return $this->killsBuildingsTotal;
    }

    public function setKillsBuildingsTotal(int $killsBuildingsTotal): self
    {
        $this->killsBuildingsTotal = $killsBuildingsTotal;

        return $this;
    }

    public function getKillsPlanesTotal(): ?int
    {
        return $this->killsPlanesTotal;
    }

    public function setKillsPlanesTotal(int $killsPlanesTotal): self
    {
        $this->killsPlanesTotal = $killsPlanesTotal;

        return $this;
    }

    public function getKillsHelicoptersTotal(): ?int
    {
        return $this->killsHelicoptersTotal;
    }

    public function setKillsHelicoptersTotal(int $killsHelicoptersTotal): self
    {
        $this->killsHelicoptersTotal = $killsHelicoptersTotal;

        return $this;
    }

    public function getKillsShipsTotal(): ?int
    {
        return $this->killsShipsTotal;
    }

    public function setKillsShipsTotal(int $killsShipsTotal): self
    {
        $this->killsShipsTotal = $killsShipsTotal;

        return $this;
    }

    public function getLandingTotal(): ?int
    {
        return $this->landingTotal;
    }

    public function setLandingTotal(int $landingTotal): self
    {
        $this->landingTotal = $landingTotal;

        return $this;
    }

    public function getTakeoffTotal(): ?int
    {
        return $this->takeoffTotal;
    }

    public function setTakeoffTotal(int $takeoffTotal): self
    {
        $this->takeoffTotal = $takeoffTotal;

        return $this;
    }

    public function getLossesPilotDeath(): ?int
    {
        return $this->lossesPilotDeath;
    }

    public function setLossesPilotDeath(int $lossesPilotDeath): self
    {
        $this->lossesPilotDeath = $lossesPilotDeath;

        return $this;
    }

    public function getLossesCrash(): ?int
    {
        return $this->lossesCrash;
    }

    public function setLossesCrash(int $lossesCrash): self
    {
        $this->lossesCrash = $lossesCrash;

        return $this;
    }

    public function getLossesEject(): ?int
    {
        return $this->lossesEject;
    }

    public function setLossesEject(int $lossesEject): self
    {
        $this->lossesEject = $lossesEject;

        return $this;
    }
}
