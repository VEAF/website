<?php

namespace App\Perun\Entity;

use App\Entity\Server;
use App\Perun\Repository\InstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_OnlineStatus")
 * @ORM\Entity(repositoryClass=InstanceRepository::class)
 */
class Instance
{
    const ALIVE_TIMEOUT = 180;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer", name="pe_OnlineStatus_instance")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_mission_theatre", nullable=true)
     */
    private ?string $theater;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_mission_name", nullable=true)
     */
    private ?string $mission;

    /**
     * @var OnlinePlayer[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=OnlinePlayer::class, mappedBy="instance")
     */
    private $perunOnlinePlayers;

    /**
     * @ORM\Column(type="integer", name="pe_OnlineStatus_server_players", nullable=true)
     */
    private ?int $playersCount;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_server_pause", nullable=true)
     */
    private ?string $pause;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_mission_multiplayer", nullable=true)
     */
    private ?string $multiplayer;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_server_realtime", nullable=true)
     */
    private ?string $realTime;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_mission_modeltime", nullable=true)
     */
    private ?string $modelTime;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_perunversion_winapp", nullable=true)
     */
    private ?string $winAppVersion;

    /**
     * @ORM\Column(type="string", length=255, name="pe_OnlineStatus_perunversion_dcshook", nullable=true)
     */
    private ?string $dcsHookVersion;

    /**
     * @ORM\Column(type="datetime", name="pe_OnlineStatus_updated", columnDefinition="DATETIME on update CURRENT_TIMESTAMP", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $updated;

    /**
     * @ORM\OneToOne(targetEntity=Server::class, cascade={"persist"}, fetch="EAGER", mappedBy="perunInstance")
     */
    private ?Server $server;

    public function __construct()
    {
        $this->perunOnlinePlayers = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Collection|OnlinePlayer[]
     */
    public function getPerunOnlinePlayers(): Collection
    {
        return $this->perunOnlinePlayers;
    }

    public function addPerunOnlinePlayer(OnlinePlayer $perunOnlinePlayer): self
    {
        if (!$this->perunOnlinePlayers->contains($perunOnlinePlayer)) {
            $this->perunOnlinePlayers[] = $perunOnlinePlayer;
            $perunOnlinePlayer->setInstance($this);
        }

        return $this;
    }

    public function removePerunOnlinePlayer(OnlinePlayer $perunOnlinePlayer): self
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

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getPlayersCountCorrected(): ?int
    {
        if ($this->playersCount > 0) {
            return $this->playersCount - 1;
        }

        return 0;
    }

    public function getDcsHookVersion(): ?string
    {
        return $this->dcsHookVersion;
    }

    public function setDcsHookVersion(?string $dcsHookVersion): void
    {
        $this->dcsHookVersion = $dcsHookVersion;
    }

    public function getModelTime(): ?string
    {
        return $this->modelTime;
    }

    public function setModelTime(?string $modelTime): self
    {
        $this->modelTime = $modelTime;

        return $this;
    }

    public function getMultiplayer(): ?string
    {
        return $this->multiplayer;
    }

    public function setMultiplayer(?string $multiplayer): self
    {
        $this->multiplayer = $multiplayer;

        return $this;
    }

    public function getPause(): ?string
    {
        return $this->pause;
    }

    public function setPause(?string $pause): self
    {
        $this->pause = $pause;

        return $this;
    }

    public function isPaused(): bool
    {
        return 'True' == $this->pause;
    }

    public function getRealTime(): ?string
    {
        return $this->realTime;
    }

    public function setRealTime(?string $realTime): self
    {
        $this->realTime = $realTime;

        return $this;
    }

    public function getWinAppVersion(): ?string
    {
        return $this->winAppVersion;
    }

    public function setWinAppVersion(?string $winAppVersion): self
    {
        $this->winAppVersion = $winAppVersion;

        return $this;
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

    public function __toString(): string
    {
        return $this->id;
    }

    public function lastUpdateSeconds(): int
    {
        if (null === $this->updated) {
            return 86400; // assume last update is 1 day old
        }

        return time() - $this->updated->getTimestamp();
    }

    // server is alive if last update is less than
    public function isAlive(): bool
    {
        return $this->lastUpdateSeconds() < static::ALIVE_TIMEOUT;
    }
}
