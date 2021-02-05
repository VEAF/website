<?php

namespace App\Entity;

use App\Repository\PerunInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_OnlineStatus")
 * @ORM\Entity(repositoryClass=PerunInstanceRepository::class)
 */
class PerunInstance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_OnlineStatus_instance")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64, name="pe_OnlineStatus_theatre")
     */
    private ?string $theater;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_name")
     */
    private ?string $mission;

    /**
     * @ORM\OneToMany(targetEntity=PerunOnlinePlayer::class, mappedBy="instance")
     */
    private $perunOnlinePlayers;

    /**
     * @ORM\Column(type="integer", name="pe_OnlineStatus_players")
     */
    private ?int $playersCount;

    /**
     * @ORM\Column(type="datetime", name="pe_OnlineStatus_updated")
     */
    private $updated;

    public function __construct()
    {
        $this->perunOnlinePlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheater(): ?string
    {
        return $this->theater;
    }

    public function setTheater(string $theater): self
    {
        $this->theater = $theater;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * @return Collection|PerunOnlinePlayer[]
     */
    public function getPerunOnlinePlayers(): Collection
    {
        return $this->perunOnlinePlayers;
    }

    public function addPerunOnlinePlayer(PerunOnlinePlayer $perunOnlinePlayer): self
    {
        if (!$this->perunOnlinePlayers->contains($perunOnlinePlayer)) {
            $this->perunOnlinePlayers[] = $perunOnlinePlayer;
            $perunOnlinePlayer->setInstance($this);
        }

        return $this;
    }

    public function removePerunOnlinePlayer(PerunOnlinePlayer $perunOnlinePlayer): self
    {
        if ($this->perunOnlinePlayers->removeElement($perunOnlinePlayer)) {
            // set the owning side to null (unless already changed)
            if ($perunOnlinePlayer->getInstance() === $this) {
                $perunOnlinePlayer->setInstance(null);
            }
        }

        return $this;
    }

    public function getPlayersCount(): ?int
    {
        return $this->playersCount;
    }

    public function setPlayersCount(int $playersCount): self
    {
        $this->playersCount = $playersCount;
        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
