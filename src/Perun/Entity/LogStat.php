<?php

namespace App\Perun\Entity;

use App\Perun\Repository\LogLoginRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="integer", name="pe_LogStats_id")
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
     * @ORM\Column(type="datetime", name="pe_LogStats_datetime")
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
     * @ORM\Column(type="integer", name="ps_kills_X")
     */
    private ?int $killsX;

    /**
     * @ORM\Column(type="integer", name="ps_pvp")
     */
    private ?int $pvp;

    /**
     * @ORM\Column(type="integer", name="ps_deaths")
     */
    private ?int $deaths;

    /**
     * @ORM\Column(type="integer", name="ps_ejections")
     */
    private ?int $ejections;

    /**
     * @ORM\Column(type="integer", name="ps_crashes")
     */
    private ?int $crashes;

    /**
     * @ORM\Column(type="integer", name="ps_teamkills")
     */
    private ?int $teamKills;

    /**
     * @ORM\Column(type="integer", name="ps_kills_planes")
     */
    private ?int $killsPlanes;

    /**
     * @ORM\Column(type="integer", name="ps_kills_helicopters")
     */
    private ?int $killsHelicopters;

    /**
     * @ORM\Column(type="integer", name="ps_kills_air_defense")
     */
    private ?int $killsAirDefense;

    /**
     * @ORM\Column(type="integer", name="ps_kills_armor")
     */
    private ?int $killsArmor;

    /**
     * @ORM\Column(type="integer", name="ps_kills_unarmed")
     */
    private ?int $killsUnarmed;

    /**
     * @ORM\Column(type="integer", name="ps_kills_infantry")
     */
    private ?int $killsInfantry;

    /**
     * @ORM\Column(type="integer", name="ps_kills_ships")
     */
    private ?int $killsShips;

    /**
     * @ORM\Column(type="integer", name="ps_kills_fortification")
     */
    private ?int $killsFortification;

    /**
     * @ORM\Column(type="integer", name="ps_kills_artillery")
     */
    private ?int $killsArtillery;

    /**
     * @ORM\Column(type="integer", name="ps_kills_other")
     */
    private ?int $killsOther;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_takeoffs")
     */
    private ?int $airefieldTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_airfield_landings")
     */
    private ?int $airfieldLandings;

    /**
     * @ORM\Column(type="integer", name="ps_ship_takeoffs")
     */
    private ?int $shipTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_ship_landings")
     */
    private ?int $shipLandings;

    /**
     * @ORM\Column(type="integer", name="ps_farp_takeoffs")
     */
    private ?int $farpTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_farp_landings")
     */
    private ?int $farpLandings;

    /**
     * @ORM\Column(type="integer", name="ps_other_landings")
     */
    private ?int $otherLandings;

    /**
     * @ORM\Column(type="integer", name="ps_other_takeoffs")
     */
    private ?int $otherTakeoffs;

    /**
     * @ORM\Column(type="integer", name="ps_time")
     */
    private ?int $time;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('?', 'RTB', 'MIA', 'KIA')", name="pe_LogStats_mstatus")
     */
    private ?string $status;

    public function getId(): ?int
    {
        return $this->id;
    }


}
