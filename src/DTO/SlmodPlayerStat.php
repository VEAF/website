<?php

namespace App\DTO;

class SlmodPlayerStat
{
    private ?int $id;
    private ?string $ucid;

    private ?\DateTime $joinAt = null;
    private ?\DateTime $lastJoinAt = null;

    /**
     * @var SlmodVariantStat[]|array
     */
    private array $variants = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getJoinAt(): ?\DateTime
    {
        return $this->joinAt;
    }

    public function setJoinAt(?\DateTime $joinAt): self
    {
        $this->joinAt = $joinAt;

        return $this;
    }

    public function getLastJoinAt(): ?\DateTime
    {
        return $this->lastJoinAt;
    }

    public function setLastJoinAt(?\DateTime $lastJoinAt): self
    {
        $this->lastJoinAt = $lastJoinAt;

        return $this;
    }

    public function getUcid(): ?string
    {
        return $this->ucid;
    }

    public function setUcid(?string $ucid): self
    {
        $this->ucid = $ucid;

        return $this;
    }

    /**
     * @return SlmodVariantStat[]|array
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(SlmodVariantStat $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }
}
