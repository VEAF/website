<?php

namespace App\DTO;

class SlmodWeaponStat
{
    private string $code = '';
    private int $numHits = 0;
    private int $shot = 0;
    private int $hit = 0;
    private int $kills = 0;
    private bool $gun = false;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getHit(): int
    {
        return $this->hit;
    }

    public function setHit(int $hit): self
    {
        $this->hit = $hit;

        return $this;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }

    public function getNumHits(): int
    {
        return $this->numHits;
    }

    public function setNumHits(int $numHits): self
    {
        $this->numHits = $numHits;

        return $this;
    }

    public function getShot(): int
    {
        return $this->shot;
    }

    public function setShot(int $shot): self
    {
        $this->shot = $shot;

        return $this;
    }

    public function isGun(): bool
    {
        return $this->gun;
    }

    public function setGun(bool $gun): self
    {
        $this->gun = $gun;

        return $this;
    }
}
