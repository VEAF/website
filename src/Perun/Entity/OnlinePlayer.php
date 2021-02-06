<?php

namespace App\Perun\Entity;

use App\Perun\Repository\OnlinePlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_OnlinePlayers")
 * @ORM\Entity(repositoryClass=OnlinePlayerRepository::class)
 */
class OnlinePlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false, name="pe_OnlinePlayers_id", referencedColumnName="pe_DataPlayers_id")
     */
    private ?Player $player;

    /**
     * @ORM\ManyToOne(targetEntity=Instance::class, inversedBy="perunOnlinePlayers")
     * @ORM\JoinColumn(nullable=false, name="pe_OnlinePlayers_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $instance;

    /**
     * @ORM\Column(type="integer", name="pe_OnlinePlayers_ping", nullable=true)
     */
    private ?int $ping;

    /**
     * @ORM\Column(type="integer", name="pe_OnlinePlayers_side", nullable=true)
     */
    private ?int $side;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlinePlayers_ucid", nullable=true)
     */
    private ?string $ucid;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlinePlayers_slot", nullable=true)
     */
    private ?string $slot;

    /**
     * @ORM\Column(type="datetime", name="pe_OnlinePlayers_updated", columnDefinition="DATETIME on update CURRENT_TIMESTAMP", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id=$id;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getPing(): ?int
    {
        return $this->ping;
    }

    public function setPing(int $ping): self
    {
        $this->ping = $ping;

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

    public function getUcid(): ?string
    {
        return $this->ucid;
    }

    public function setUcid(string $ucid): self
    {
        $this->ucid = $ucid;

        return $this;
    }

    public function getSide(): ?int
    {
        return $this->side;
    }

    public function setSide(?int $side): self
    {
        $this->side = $side;

        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getSlot(): ?string
    {
        return $this->slot;
    }

    public function setSlot(?string $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

}
