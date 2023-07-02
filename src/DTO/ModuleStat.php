<?php

namespace App\DTO;

use App\Entity\Module;

/**
 * @deprecated https://github.com/VEAF/website/issues/365
 */
class ModuleStat extends AbstractStat
{
    private ?Module $module;

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
