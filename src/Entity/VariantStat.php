<?php

namespace App\Entity;

use App\Repository\VariantStatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="variant_idx", columns={"server_id", "variant_id", "player_id"}),
 * })
 * @ORM\Entity(repositoryClass=VariantStatRepository::class)
 */
class VariantStat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Server $server;

    /**
     * @ORM\ManyToOne(targetEntity=Variant::class, inversedBy="stats")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Variant $variant;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Player $player;

    /**
     * @ORM\Column(type="float")
     */
    private float $total = 0;

    /**
     * @ORM\Column(type="float")
     */
    private float $inAir = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getInAir(): ?float
    {
        return $this->inAir;
    }

    public function setInAir(float $inAir): self
    {
        $this->inAir = $inAir;

        return $this;
    }
}
