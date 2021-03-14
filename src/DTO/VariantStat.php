<?php

namespace App\DTO;

use App\Entity\Variant;

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
