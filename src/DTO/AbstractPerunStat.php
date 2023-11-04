<?php

namespace App\DTO;

abstract class AbstractPerunStat
{
    private float $time = 0.0;
    private int $pvp = 0;
    private int $deaths = 0;
    private int $ejections = 0;
    private int $crashes = 0;
    private int $teamKills = 0;
    private int $killsPlanes = 0;
    private int $killsHelicopters = 0;
    private int $killsAirDefense = 0;
    private int $killsArmor = 0;
    private int $killsUnarmed = 0;
    private int $killsInfantry = 0;
    private int $killsFortification = 0;
    private int $killsArtillery = 0;
    private int $killsOther = 0;
    private int $airfieldTakeoffs = 0;
    private int $airfieldLandings = 0;
    private int $shipTakeoffs = 0;
    private int $shipLandings = 0;
    private int $farpTakeoffs = 0;
    private int $farpLandings = 0;
    private int $otherTakeoffs = 0;
    private int $otherLandings = 0;

    public function __construct(
        $time = 0,
        $pvp = 0,
        $deaths = 0,
        $ejections = 0,
        $crashes = 0,
        $teamKills = 0,
        $killsPlanes = 0,
        $killsHelicopters = 0,
        $killsAirDefense = 0,
        $killsArmor = 0,
        $killsUnarmed = 0,
        $killsInfantry = 0,
        $killsFortification = 0,
        $killsArtillery = 0,
        $killsOther = 0,
        $airfieldTakeoffs = 0,
        $airfieldLandings = 0,
        $shipTakeoffs = 0,
        $shipLandings = 0,
        $farpTakeoffs = 0,
        $farpLandings = 0,
        $otherTakeoffs = 0,
        $otherLandings = 0)
    {
        $this->time = $time;
        $this->pvp = $pvp;
        $this->deaths = $deaths;
        $this->ejections = $ejections;
        $this->crashes = $crashes;
        $this->teamKills = $teamKills;
        $this->killsPlanes = $killsPlanes;
        $this->killsHelicopters = $killsHelicopters;
        $this->killsAirDefense = $killsAirDefense;
        $this->killsArmor = $killsArmor;
        $this->killsUnarmed = $killsUnarmed;
        $this->killsInfantry = $killsInfantry;
        $this->killsFortification = $killsFortification;
        $this->killsArtillery = $killsArtillery;
        $this->killsOther = $killsOther;
        $this->airfieldTakeoffs = $airfieldTakeoffs;
        $this->airfieldLandings = $airfieldLandings;
        $this->shipTakeoffs = $shipTakeoffs;
        $this->shipLandings = $shipLandings;
        $this->farpTakeoffs = $farpTakeoffs;
        $this->farpLandings = $farpLandings;
        $this->otherTakeoffs = $otherTakeoffs;
        $this->otherLandings = $otherLandings;
    }

    public function setFromRow(array $row): self
    {
        foreach ([
                     'time',
                     'pvp',
                     'deaths',
                     'ejections',
                     'crashes',
                     'teamKills',
                     'killsPlanes',
                     'killsHelicopters',
                     'killsAirDefense',
                     'killsArmor',
                     'killsUnarmed',
                     'killsInfantry',
                     'killsFortification',
                     'killsArtillery',
                     'killsOther',
                     'airfieldTakeoffs',
                     'airfieldLandings',
                     'shipTakeoffs',
                     'shipLandings',
                     'farpTakeoffs',
                     'farpLandings',
                     'otherTakeoffs',
                     'otherLandings',
                 ] as $field) {
            if (isset($row[$field])) {
                $this->$field = $row[$field];
            }
        }

        return $this;
    }

    public function getAirfieldLandings(): int
    {
        return $this->airfieldLandings;
    }

    public function getAirfieldTakeoffs(): int
    {
        return $this->airfieldTakeoffs;
    }

    public function getCrashes(): int
    {
        return $this->crashes;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getEjections(): int
    {
        return $this->ejections;
    }

    public function getFarpLandings(): int
    {
        return $this->farpLandings;
    }

    public function getFarpTakeoffs(): int
    {
        return $this->farpTakeoffs;
    }

    public function getKillsAirDefense(): int
    {
        return $this->killsAirDefense;
    }

    public function getKillsArmor(): int
    {
        return $this->killsArmor;
    }

    public function getKillsArtillery(): int
    {
        return $this->killsArtillery;
    }

    public function getKillsFortification(): int
    {
        return $this->killsFortification;
    }

    public function getKillsHelicopters(): int
    {
        return $this->killsHelicopters;
    }

    public function getKillsInfantry(): int
    {
        return $this->killsInfantry;
    }

    public function getKillsOther(): int
    {
        return $this->killsOther;
    }

    public function getKillsPlanes(): int
    {
        return $this->killsPlanes;
    }

    public function getKillsUnarmed(): int
    {
        return $this->killsUnarmed;
    }

    public function getOtherLandings(): int
    {
        return $this->otherLandings;
    }

    public function getOtherTakeoffs(): int
    {
        return $this->otherTakeoffs;
    }

    public function getPvp(): int
    {
        return $this->pvp;
    }

    public function getShipLandings(): int
    {
        return $this->shipLandings;
    }

    public function getShipTakeoffs(): int
    {
        return $this->shipTakeoffs;
    }

    public function getTeamKills(): int
    {
        return $this->teamKills;
    }

    public function getTime(): float
    {
        return $this->time;
    }

    public function setTime(float $time): void
    {
        $this->time = $time;
    }

    public function getTotalTakeoffs(): int
    {
        return $this->farpTakeoffs + $this->shipTakeoffs + $this->otherTakeoffs + $this->airfieldTakeoffs;
    }

    public function getTotalLandings(): int
    {
        return $this->farpLandings + $this->shipLandings + $this->otherLandings + $this->airfieldLandings;
    }

    public function getKillsGroundUnits(): int
    {
        return $this->killsArtillery + $this->killsArmor + $this->killsAirDefense + $this->killsInfantry + $this->killsUnarmed;
    }

    public function getKillsBuildings(): int
    {
        return $this->killsOther + $this->killsFortification;
    }
}
