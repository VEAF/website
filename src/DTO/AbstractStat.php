<?php

namespace App\DTO;

abstract class AbstractStat
{
    private float $totalHours = 0.0;
    private float $inAirHours = 0.0;
    private int $killsGroundUnitsTotal = 0;
    private int $killsBuildingsTotal = 0;
    private int $killsPlanesTotal = 0;
    private int $killsHelicoptersTotal = 0;
    private int $killsShipsTotal = 0;
    private int $landingTotal = 0;
    private int $takeoffTotal = 0;
    private int $lossesPilotDeath = 0;
    private int $lossesCrash = 0;
    private int $lossesEject = 0;

    public function __construct(
        float $totalHours = 0.0,
        float $inAirHours = 0.0,
        int $killsGroundUnitsTotal = 0,
        int $killsBuildingsTotal = 0,
        int $killsPlanesTotal = 0,
        int $killsHelicoptersTotal = 0,
        int $killsShipsTotal = 0,
        int $landingTotal = 0,
        int $takeoffTotal = 0,
        int $lossesPilotDeath = 0,
        int $lossesCrash = 0,
        int $lossesEject = 0
    ) {
        $this->totalHours = $totalHours;
        $this->inAirHours = $inAirHours;
        $this->killsGroundUnitsTotal = $killsGroundUnitsTotal;
        $this->killsBuildingsTotal = $killsBuildingsTotal;
        $this->killsPlanesTotal = $killsPlanesTotal;
        $this->killsHelicoptersTotal = $killsHelicoptersTotal;
        $this->killsShipsTotal = $killsShipsTotal;
        $this->landingTotal = $landingTotal;
        $this->takeoffTotal = $takeoffTotal;
        $this->lossesPilotDeath = $lossesPilotDeath;
        $this->lossesCrash = $lossesCrash;
        $this->lossesEject = $lossesEject;
    }

    public function getInAirHours(): ?float
    {
        return $this->inAirHours;
    }

    public function setInAirHours(float $inAirHours): self
    {
        $this->inAirHours = $inAirHours;

        return $this;
    }

    public function getTotalHours(): float
    {
        return $this->totalHours;
    }

    public function setTotalHours(float $totalHours): self
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    public function getKillsBuildingsTotal(): int
    {
        return $this->killsBuildingsTotal;
    }

    public function setKillsBuildingsTotal(int $killsBuildingsTotal): self
    {
        $this->killsBuildingsTotal = $killsBuildingsTotal;

        return $this;
    }

    public function getKillsGroundUnitsTotal(): int
    {
        return $this->killsGroundUnitsTotal;
    }

    public function setKillsGroundUnitsTotal(int $killsGroundUnitsTotal): self
    {
        $this->killsGroundUnitsTotal = $killsGroundUnitsTotal;

        return $this;
    }

    public function getKillsPlanesTotal(): int
    {
        return $this->killsPlanesTotal;
    }

    public function setKillsPlanesTotal(int $killsPlanesTotal): self
    {
        $this->killsPlanesTotal = $killsPlanesTotal;

        return $this;
    }

    public function getKillsHelicoptersTotal(): int
    {
        return $this->killsHelicoptersTotal;
    }

    public function setKillsHelicoptersTotal(int $killsHelicoptersTotal): self
    {
        $this->killsHelicoptersTotal = $killsHelicoptersTotal;

        return $this;
    }

    public function getKillsShipsTotal(): int
    {
        return $this->killsShipsTotal;
    }

    public function setKillsShipsTotal(int $killsShipsTotal): self
    {
        $this->killsShipsTotal = $killsShipsTotal;

        return $this;
    }

    public function getLandingTotal(): int
    {
        return $this->landingTotal;
    }

    public function setLandingTotal(int $landingTotal): self
    {
        $this->landingTotal = $landingTotal;

        return $this;
    }

    public function getLossesCrash(): int
    {
        return $this->lossesCrash;
    }

    public function setLossesCrash(int $lossesCrash): self
    {
        $this->lossesCrash = $lossesCrash;

        return $this;
    }

    public function getLossesEject(): int
    {
        return $this->lossesEject;
    }

    public function setLossesEject(int $lossesEject): self
    {
        $this->lossesEject = $lossesEject;

        return $this;
    }

    public function getLossesPilotDeath(): int
    {
        return $this->lossesPilotDeath;
    }

    public function setLossesPilotDeath(int $lossesPilotDeath): self
    {
        $this->lossesPilotDeath = $lossesPilotDeath;

        return $this;
    }

    public function getTakeoffTotal(): int
    {
        return $this->takeoffTotal;
    }

    public function setTakeoffTotal(int $takeoffTotal): self
    {
        $this->takeoffTotal = $takeoffTotal;

        return $this;
    }

    public function setFromRow(array $row): self
    {
        foreach ([
                     'totalHours',
                     'inAirHours',
                     'killsGroundUnitsTotal',
                     'killsBuildingsTotal',
                     'killsPlanesTotal',
                     'killsHelicoptersTotal',
                     'killsShipsTotal',
                     'landingTotal',
                     'takeoffTotal',
                     'lossesPilotDeath',
                     'lossesEject',
                     'lossesCrash',
                 ] as $field) {
            if (isset($row[$field])) {
                $this->$field = $row[$field];
            }
        }

        return $this;
    }
}
