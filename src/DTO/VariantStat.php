<?php

namespace App\DTO;

use App\Entity\Variant;

/**
 * @deprecated https://github.com/VEAF/website/issues/365
 */
class VariantStat extends AbstractStat
{
    private ?Variant $variant;

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }
}
