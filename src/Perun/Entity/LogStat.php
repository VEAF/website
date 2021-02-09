<?php

namespace App\Perun\Entity;

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
    private ?int $id;

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
    private ?int $masterSlot;

    /**
     * @ORM\Column(type="integer", name="pe_LogStats_seat", nullable=true)
     */
    private ?int $seat;

    /**
     * @ORM\Column(type="integer", name="ps_kills_X", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsX;

    /**
     * @ORM\Column(type="integer", name="ps_pvp", options={"unsigned"=true, "default": 0})
     */
    private ?int $pvp;

    /**
     * @ORM\Column(type="integer", name="ps_deaths", options={"unsigned"=true, "default": 0})
     */
    private ?int $deaths;

    /**
     * @ORM\Column(type="integer", name="ps_ejections", options={"unsigned"=true, "default": 0})
     */
    private ?int $ejections;

    /**
     * @ORM\Column(type="integer", name="ps_crashes", options={"unsigned"=true, "default": 0})
     */
    private ?int $crashes;

    /**
     * @ORM\Column(type="integer", name="ps_teamkills", options={"unsigned"=true, "default": 0})
     */
    private ?int $teamKills;

    /**
     * @ORM\Column(type="integer", name="ps_kills_planes", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsPlanes;

    /**
     * @ORM\Column(type="integer", name="ps_kills_helicopters", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsHelicopters;

    /**
     * @ORM\Column(type="integer", name="ps_kills_air_defense", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsAirDefense;

    /**
     * @ORM\Column(type="integer", name="ps_kills_armor", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsArmor;

    /**
     * @ORM\Column(type="integer", name="ps_kills_unarmed", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsUnarmed;

    /**
     * @ORM\Column(type="integer", name="ps_kills_infantry", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsInfantry;

    /**
     * @ORM\Column(type="integer", name="ps_kills_ships", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsShips;

    /**
     * @ORM\Column(type="integer", name="ps_kills_fortification", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsFortification;

    /**
     * @ORM\Column(type="integer", name="ps_kills_artillery", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsArtillery;

    /**
     * @ORM\Column(type="integer", name="ps_kills_other", options={"unsigned"=true, "default": 0})
     */
    private ?int $killsOther;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private ?int $airefieldTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_landings", options={"unsigned"=true, "default": 0})
     */
    private ?int $airfieldLandings;

    /**
     * @ORM\Column(type="integer", name="ps_ship_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private ?int $shipTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_ship_landings", options={"unsigned"=true, "default": 0})
     */
    private ?int $shipLandings;

    /**
     * @ORM\Column(type="integer", name="ps_farp_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private ?int $farpTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_farp_landings", options={"unsigned"=true, "default": 0})
     */
    private ?int $farpLandings;

    /**
     * @ORM\Column(type="integer", name="ps_other_landings", options={"unsigned"=true, "default": 0})
     */
    private ?int $otherLandings;

    /**
     * @ORM\Column(type="integer", name="ps_other_takeoffs", options={"unsigned"=true, "default": 0})
     */
    private ?int $otherTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_time", options={"unsigned"=true, "default": 0})
     */
    private ?int $time;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('?', 'RTB', 'MIA', 'KIA')", name="pe_LogStats_mstatus", nullable=true)
     */
    private ?string $status;

    public function getId(): ?int
    {
        return $this->id;
    }
}
