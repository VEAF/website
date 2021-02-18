<?php

namespace App\DTO;

class SlmodVariantStat
{
    private ?string $variantCode;

    private ?float $total;
    private ?float $inAir;

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
}