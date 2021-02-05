<?php

namespace App\Entity;

use App\Repository\PerunOnlinePlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_OnlinePlayers")
 * @ORM\Entity(repositoryClass=PerunOnlinePlayerRepository::class)
 */
class PerunOnlinePlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_OnlinePlayers_id")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=PerunInstance::class, inversedBy="perunOnlinePlayers")
     * @ORM\JoinColumn(nullable=false, name="pe_OnlinePlayers_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?PerunInstance $instance;

    /**
     * @ORM\Column(type="integer", name="pe_OnlinePlayers_ping")
     */
    private ?int $ping;

    /**
     * @ORM\Column(type="integer", name="pe_OnlinePlayers_side")
     */
    private ?int $side;

    private ?PerunPlayer $player;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlinePlayers_ucid")
     */
    private ?string $ucid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstance(): ?PerunInstance
    {
        return $this->instance;
    }

    public function setInstance(?PerunInstance $instance): self
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

    public function getPlayer(): ?PerunPlayer
    {
        return $this->player;
    }

    public function setPlayer(?PerunPlayer $player): self
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

}
