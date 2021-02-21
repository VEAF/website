<?php

namespace App\DTO;

use App\Entity\Module;

class ModuleStat
{
    private ?Module $module;

    private ?float $totalHours = 0.0;
    private ?float $inAirHours = 0.0;

    public function getInAirHours(): ?float
    {
        return $this->inAirHours;
    }

    public function setInAirHours(?float $inAirHours): self
    {
        $this->inAirHours = $inAirHours;

        return $this;
    }

    public function getTotalHours(): ?float
    {
        return $this->totalHours;
    }

    public function setTotalHours(?float $totalHours): self
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): void
    {
        $this->module = $module;
    }
}
