<?php

namespace App\Perun\Entity;

use App\Perun\Repository\LogStatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_LogStats")
 * @ORM\Entity(repositoryClass=LogStatRepository::class)
 */
class LogStat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", name="pe_LogStats_id")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=DataMissionHash::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogStats_missionhash_id", referencedColumnName="pe_DataMissionHashes_id", nullable=true)
     */
    private ?DataMissionHash $mission;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogStats_playerid", referencedColumnName="pe_DataPlayers_id", nullable=true)
     */
    private ?Player $player;

    /**
     * @ORM\ManyToOne(targetEntity=DataType::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogStats_typeid", referencedColumnName="pe_DataTypes_id", nullable=true)
     */
    private ?DataType $type;

    /**
     * @ORM\Column(type="datetime", name="pe_LogStats_datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $datetime;

    /**
     * @ORM\Column(type="integer", name="pe_LogStats_masterslot", nullable=true)
     */
    private int $masterSlot = 0;

    /**
     * @ORM\Column(type="integer", name="pe_LogStats_seat", nullable=true)
     */
    private int $seat = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_X", options={"unsigned"=true, "default": 0})
     */
    private int $killsX = 0;

    /**
     * @ORM\Column(type="integer", name="ps_pvp", options={"unsigned"=true, "default": 0})
     */
    private int $pvp = 0;

    /**
     * @ORM\Column(type="integer", name="ps_deaths", options={"unsigned"=true, "default": 0})
     */
    private int $deaths = 0;

    /**
     * @ORM\Column(type="integer", name="ps_ejections", options={"unsigned"=true, "default": 0})
     */
    private int $ejections = 0;

    /**
     * @ORM\Column(type="integer", name="ps_crashes", options={"unsigned"=true, "default": 0})
     */
    private int $crashes = 0;

    /**
     * @ORM\Column(type="integer", name="ps_teamkills", options={"unsigned"=true, "default": 0})
     */
    private int $teamKills = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_planes", options={"unsigned"=true, "default": 0})
     */
    private int $killsPlanes = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_helicopters", options={"unsigned"=true, "default": 0})
     */
    private int $killsHelicopters = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_air_defense", options={"unsigned"=true, "default": 0})
     */
    private int $killsAirDefense = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_armor", options={"unsigned"=true, "default": 0})
     */
    private int $killsArmor = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_unarmed", options={"unsigned"=true, "default": 0})
     */
    private int $killsUnarmed = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_infantry", options={"unsigned"=true, "default": 0})
     */
    private int $killsInfantry = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_ships", options={"unsigned"=true, "default": 0})
     */
    private int $killsShips = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_fortification", options={"unsigned"=true, "default": 0})
     */
    private int $killsFortification = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_artillery", options={"unsigned"=true, "default": 0})
     */
    private int $killsArtillery = 0;

    /**
     * @ORM\Column(type="integer", name="ps_kills_other", options={"unsigned"=true, "default": 0})
     */
    private int $killsOther = 0;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private int $airfieldTakeoffs = 0;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_landings", options={"unsigned"=true, "default": 0})
     */
    private int $airfieldLandings = 0;

    /**
     * @ORM\Column(type="integer", name="ps_ship_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private int $shipTakeoffs = 0;

    /**
     * @ORM\Column(type="integer", name="ps_ship_landings", options={"unsigned"=true, "default": 0})
     */
    private int $shipLandings = 0;

    /**
     * @ORM\Column(type="integer", name="ps_farp_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private int $farpTakeoffs = 0;

    /**
     * @ORM\Column(type="integer", name="ps_farp_landings", options={"unsigned"=true, "default": 0})
     */
    private int $farpLandings = 0;

    /**
     * @ORM\Column(type="integer", name="ps_other_landings", options={"unsigned"=true, "default": 0})
     */
    private int $otherLandings = 0;

    /**
     * @ORM\Column(type="integer", name="ps_other_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private int $otherTakeoffs = 0;

    /**
     * @ORM\Column(type="integer", name="ps_time", options={"unsigned"=true, "default": 0})
     */
    private int $time = 0;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('?', 'RTB', 'MIA', 'KIA')", name="pe_LogStats_mstatus", nullable=true)
     */
    private ?string $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getMission(): ?DataMissionHash
    {
        return $this->mission;
    }

    public function setMission(?DataMissionHash $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getTeamKills(): int
    {
        return $this->teamKills;
    }

    public function getPvp(): int
    {
        return $this->pvp;
    }

    public function getShipTakeoffs(): int
    {
        return $this->shipTakeoffs;
    }

    public function getShipLandings(): int
    {
        return $this->shipLandings;
    }

    public function getOtherTakeoffs(): int
    {
        return $this->otherTakeoffs;
    }

    public function getOtherLandings(): int
    {
        return $this->otherLandings;
    }

    public function getKillsUnarmed(): int
    {
        return $this->killsUnarmed;
    }

    public function getKillsPlanes(): int
    {
        return $this->killsPlanes;
    }

    public function getKillsOther(): int
    {
        return $this->killsOther;
    }

    public function getKillsInfantry(): int
    {
        return $this->killsInfantry;
    }

    public function getKillsHelicopters(): int
    {
        return $this->killsHelicopters;
    }

    public function getKillsFortification(): int
    {
        return $this->killsFortification;
    }

    public function getKillsArtillery(): int
    {
        return $this->killsArtillery;
    }

    public function getKillsArmor(): int
    {
        return $this->killsArmor;
    }

    public function getKillsAirDefense(): int
    {
        return $this->killsAirDefense;
    }

    public function getFarpTakeoffs(): int
    {
        return $this->farpTakeoffs;
    }

    public function getFarpLandings(): int
    {
        return $this->farpLandings;
    }

    public function getEjections(): int
    {
        return $this->ejections;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getCrashes(): int
    {
        return $this->crashes;
    }

    public function getAirfieldTakeoffs(): int
    {
        return $this->airfieldTakeoffs;
    }

    public function getAirfieldLandings(): int
    {
        return $this->airfieldLandings;
    }

    public function getKillsShips(): int
    {
        return $this->killsShips;
    }

    public function getKillsX(): int
    {
        return $this->killsX;
    }

    public function getMasterSlot(): int
    {
        return $this->masterSlot;
    }

    public function getSeat(): int
    {
        return $this->seat;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getType(): ?DataType
    {
        return $this->type;
    }

    public function getTotalTakeoffs(): int
    {
        return $this->farpTakeoffs + $this->shipTakeoffs + $this->otherTakeoffs + $this->airfieldTakeoffs;
    }

    public function getTotalLandings(): int
    {
        return $this->farpLandings + $this->shipLandings + $this->otherLandings + $this->airfieldLandings;
    }
}
