<?php

namespace App\Perun\DTO;

/**
 * Data Transfer Object for DCS Bot statistics row.
 */
class DcsBotStatistic
{
    private int $missionId;
    private string $playerUcid;
    private string $slot;
    private int $side = 0;
    private int $kills = 0;
    private int $pvp = 0;
    private int $deaths = 0;
    private int $ejections = 0;
    private int $crashes = 0;
    private int $teamkills = 0;
    private int $killsPlanes = 0;
    private int $killsHelicopters = 0;
    private int $killsShips = 0;
    private int $killsSams = 0;
    private int $killsGround = 0;
    private int $takeoffs = 0;
    private int $landings = 0;
    private \DateTime $hopOn;
    private ?\DateTime $hopOff = null;

    /**
     * Creates a DcsBotStatistic from a database row.
     */
    public static function fromRow(array $row): self
    {
        $dto = new self();
        $dto->missionId = (int) $row['mission_id'];
        $dto->playerUcid = $row['player_ucid'];
        $dto->slot = $row['slot'];
        $dto->side = (int) ($row['side'] ?? 0);
        $dto->kills = (int) ($row['kills'] ?? 0);
        $dto->pvp = (int) ($row['pvp'] ?? 0);
        $dto->deaths = (int) ($row['deaths'] ?? 0);
        $dto->ejections = (int) ($row['ejections'] ?? 0);
        $dto->crashes = (int) ($row['crashes'] ?? 0);
        $dto->teamkills = (int) ($row['teamkills'] ?? 0);
        $dto->killsPlanes = (int) ($row['kills_planes'] ?? 0);
        $dto->killsHelicopters = (int) ($row['kills_helicopters'] ?? 0);
        $dto->killsShips = (int) ($row['kills_ships'] ?? 0);
        $dto->killsSams = (int) ($row['kills_sams'] ?? 0);
        $dto->killsGround = (int) ($row['kills_ground'] ?? 0);
        $dto->takeoffs = (int) ($row['takeoffs'] ?? 0);
        $dto->landings = (int) ($row['landings'] ?? 0);

        $dto->hopOn = new \DateTime($row['hop_on']);
        $dto->hopOff = null !== $row['hop_off'] ? new \DateTime($row['hop_off']) : null;

        return $dto;
    }

    /**
     * Calculates session duration in seconds.
     */
    public function getSessionDuration(): int
    {
        if (null === $this->hopOff) {
            return 0;
        }

        return $this->hopOff->getTimestamp() - $this->hopOn->getTimestamp();
    }

    public function getMissionId(): int
    {
        return $this->missionId;
    }

    public function getPlayerUcid(): string
    {
        return $this->playerUcid;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }

    public function getSide(): int
    {
        return $this->side;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getPvp(): int
    {
        return $this->pvp;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getEjections(): int
    {
        return $this->ejections;
    }

    public function getCrashes(): int
    {
        return $this->crashes;
    }

    public function getTeamkills(): int
    {
        return $this->teamkills;
    }

    public function getKillsPlanes(): int
    {
        return $this->killsPlanes;
    }

    public function getKillsHelicopters(): int
    {
        return $this->killsHelicopters;
    }

    public function getKillsShips(): int
    {
        return $this->killsShips;
    }

    public function getKillsSams(): int
    {
        return $this->killsSams;
    }

    public function getKillsGround(): int
    {
        return $this->killsGround;
    }

    public function getTakeoffs(): int
    {
        return $this->takeoffs;
    }

    public function getLandings(): int
    {
        return $this->landings;
    }

    public function getHopOn(): \DateTime
    {
        return $this->hopOn;
    }

    public function getHopOff(): ?\DateTime
    {
        return $this->hopOff;
    }
}
