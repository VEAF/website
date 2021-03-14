<?php

namespace App\DTO;

use App\Entity\WeaponStat;

class SlmodVariantStat
{
    private ?string $variantCode;

    private ?float $total;
    private ?float $inAir;
    private ?int $killsGroundUnitsTotal;
    private ?int $killsBuildingsTotal;
    private ?int $killsPlanesTotal;
    private ?int $killsHelicoptersTotal;
    private ?int $killsShipsTotal;
    private ?int $landingTotal;
    private ?int $takeoffTotal;
    private ?int $lossesPilotDeath;
    private ?int $lossesCrash;
    private ?int $lossesEject;

    /** @var SlmodWeaponStat|array */
    private array $weapons = [];

    public function getVariantCode(): ?string
    {
        return $this->variantCode;
    }

    public function setVariantCode(?string $variantCode): self
    {
        $this->variantCode = $variantCode;

        return $this;
    }

    public function getInAir(): ?float
    {
        return $this->inAir;
    }

    public function setInAir(?float $inAir): self
    {
        $this->inAir = $inAir;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return SlmodWeaponStat[]|array
     */
    public function getWeapons(): array
    {
        return $this->weapons;
    }

    public function addWeapon(SlmodWeaponStat $weapon): self
    {
        $this->weapons[] = $weapon;

        return $this;
    }

    public function getKillsBuildingsTotal(): ?int
    {
        return $this->killsBuildingsTotal;
    }

    public function setKillsBuildingsTotal(?int $killsBuildingsTotal): self
    {
        $this->killsBuildingsTotal = $killsBuildingsTotal;

        return $this;
    }

    public function getKillsGroundUnitsTotal(): ?int
    {
        return $this->killsGroundUnitsTotal;
    }

    public function setKillsGroundUnitsTotal(?int $killsGroundUnitsTotal): self
    {
        $this->killsGroundUnitsTotal = $killsGroundUnitsTotal;

        return $this;
    }

    public function getKillsPlanesTotal(): ?int
    {
        return $this->killsPlanesTotal;
    }

    public function setKillsPlanesTotal(?int $killsPlanesTotal): self
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

    public function setLandingTotal(?int $landingTotal): self
    {
        $this->landingTotal = $landingTotal;

        return $this;
    }

    public function getLossesCrash(): ?int
    {
        return $this->lossesCrash;
    }

    public function setLossesCrash(?int $lossesCrash): self
    {
        $this->lossesCrash = $lossesCrash;

        return $this;
    }

    public function getLossesEject(): ?int
    {
        return $this->lossesEject;
    }

    public function setLossesEject(?int $lossesEject): self
    {
        $this->lossesEject = $lossesEject;

        return $this;
    }

    public function getLossesPilotDeath(): ?int
    {
        return $this->lossesPilotDeath;
    }

    public function setLossesPilotDeath(?int $lossesPilotDeath): self
    {
        $this->lossesPilotDeath = $lossesPilotDeath;

        return $this;
    }

    public function getTakeoffTotal(): ?int
    {
        return $this->takeoffTotal;
    }

    public function setTakeoffTotal(?int $takeoffTotal): self
    {
        $this->takeoffTotal = $takeoffTotal;

        return $this;
    }
}
