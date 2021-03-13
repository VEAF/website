<?php

namespace App\Entity;

use App\Repository\WeaponStatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeaponStatRepository::class)
 */
class WeaponStat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Weapon::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Weapon $weapon = null;

    /**
     * @ORM\ManyToOne(targetEntity=VariantStat::class, inversedBy="weapons")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?VariantStat $variantStat = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $numHits = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shot = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $hit = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $kills = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $gun = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeapon(): ?Weapon
    {
        return $this->weapon;
    }

    public function setWeapon(?Weapon $weapon): self
    {
        $this->weapon = $weapon;

        return $this;
    }

    public function getVariantStat(): ?VariantStat
    {
        return $this->variantStat;
    }

    public function setVariantStat(?VariantStat $variantStat): self
    {
        $this->variantStat = $variantStat;

        return $this;
    }

    public function getNumHits(): ?int
    {
        return $this->numHits;
    }

    public function setNumHits(int $numHits): self
    {
        $this->numHits = $numHits;

        return $this;
    }

    public function getShot(): ?int
    {
        return $this->shot;
    }

    public function setShot(int $shot): self
    {
        $this->shot = $shot;

        return $this;
    }

    public function getHit(): ?int
    {
        return $this->hit;
    }

    public function setHit(int $hit): self
    {
        $this->hit = $hit;

        return $this;
    }

    public function getKills(): ?int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }

    public function getGun(): ?bool
    {
        return $this->gun;
    }

    public function setGun(bool $gun): self
    {
        $this->gun = $gun;

        return $this;
    }
}
