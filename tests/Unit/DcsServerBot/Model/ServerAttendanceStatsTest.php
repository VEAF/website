<?php

namespace App\Tests\Unit\DcsServerBot\Model;

use DcsServerBot\Model\ServerAttendanceStats;
use DcsServerBot\Model\TopTheatre;
use DcsServerBot\Model\TopMission;
use DcsServerBot\Model\TopModule;
use PHPUnit\Framework\TestCase;

class ServerAttendanceStatsTest extends TestCase
{
    public function testCreateAttendanceStatsWithBasicFields(): void
    {
        $data = [
            'current_players' => 5,
            'unique_players_24h' => 25,
            'total_playtime_hours_24h' => 50.5,
            'discord_members_24h' => 15,
            'unique_players_7d' => 100,
            'total_playtime_hours_7d' => 350.0,
            'discord_members_7d' => 60,
            'unique_players_30d' => 250,
            'total_playtime_hours_30d' => 1500.0,
            'discord_members_30d' => 150,
        ];

        $stats = new ServerAttendanceStats($data);

        $this->assertEquals(5, $stats->getCurrentPlayers());
        $this->assertEquals(25, $stats->getUniquePlayers24h());
        $this->assertEquals(50.5, $stats->getTotalPlaytimeHours24h());
        $this->assertEquals(15, $stats->getDiscordMembers24h());
        $this->assertEquals(100, $stats->getUniquePlayers7d());
        $this->assertEquals(350.0, $stats->getTotalPlaytimeHours7d());
        $this->assertEquals(60, $stats->getDiscordMembers7d());
        $this->assertEquals(250, $stats->getUniquePlayers30d());
        $this->assertEquals(1500.0, $stats->getTotalPlaytimeHours30d());
        $this->assertEquals(150, $stats->getDiscordMembers30d());
    }

    public function testAttendanceStatsWithNullableCombatFields(): void
    {
        $data = [
            'current_players' => 0,
            'unique_players_24h' => 0,
            'total_playtime_hours_24h' => 0.0,
            'discord_members_24h' => 0,
            'unique_players_7d' => 0,
            'total_playtime_hours_7d' => 0.0,
            'discord_members_7d' => 0,
            'unique_players_30d' => 0,
            'total_playtime_hours_30d' => 0.0,
            'discord_members_30d' => 0,
            'total_sorties' => null,
            'total_kills' => null,
            'total_deaths' => null,
            'total_pvp_kills' => null,
            'total_pvp_deaths' => null,
        ];

        $stats = new ServerAttendanceStats($data);

        $this->assertNull($stats->getTotalSorties());
        $this->assertNull($stats->getTotalKills());
        $this->assertNull($stats->getTotalDeaths());
        $this->assertNull($stats->getTotalPvpKills());
        $this->assertNull($stats->getTotalPvpDeaths());
    }

    public function testAttendanceStatsWithCombatFields(): void
    {
        $data = [
            'current_players' => 10,
            'unique_players_24h' => 50,
            'total_playtime_hours_24h' => 100.0,
            'discord_members_24h' => 30,
            'unique_players_7d' => 150,
            'total_playtime_hours_7d' => 500.0,
            'discord_members_7d' => 90,
            'unique_players_30d' => 400,
            'total_playtime_hours_30d' => 2000.0,
            'discord_members_30d' => 250,
            'total_sorties' => 1000,
            'total_kills' => 500,
            'total_deaths' => 200,
            'total_pvp_kills' => 50,
            'total_pvp_deaths' => 30,
        ];

        $stats = new ServerAttendanceStats($data);

        $this->assertEquals(1000, $stats->getTotalSorties());
        $this->assertEquals(500, $stats->getTotalKills());
        $this->assertEquals(200, $stats->getTotalDeaths());
        $this->assertEquals(50, $stats->getTotalPvpKills());
        $this->assertEquals(30, $stats->getTotalPvpDeaths());
    }

    public function testAttendanceStatsWithTopTheatres(): void
    {
        $theatre1 = new TopTheatre(['theatre' => 'Caucasus', 'playtime_hours' => 100]);
        $theatre2 = new TopTheatre(['theatre' => 'Syria', 'playtime_hours' => 80]);

        $data = [
            'current_players' => 5,
            'unique_players_24h' => 20,
            'total_playtime_hours_24h' => 40.0,
            'discord_members_24h' => 10,
            'unique_players_7d' => 80,
            'total_playtime_hours_7d' => 200.0,
            'discord_members_7d' => 50,
            'unique_players_30d' => 200,
            'total_playtime_hours_30d' => 1000.0,
            'discord_members_30d' => 120,
            'top_theatres' => [$theatre1, $theatre2],
        ];

        $stats = new ServerAttendanceStats($data);

        $topTheatres = $stats->getTopTheatres();
        $this->assertCount(2, $topTheatres);
        $this->assertEquals('Caucasus', $topTheatres[0]->getTheatre());
        $this->assertEquals(100, $topTheatres[0]->getPlaytimeHours());
        $this->assertEquals('Syria', $topTheatres[1]->getTheatre());
        $this->assertEquals(80, $topTheatres[1]->getPlaytimeHours());
    }

    public function testAttendanceStatsWithTopMissions(): void
    {
        $mission1 = new TopMission(['mission_name' => 'Operation Blue Flag', 'playtime_hours' => 200]);
        $mission2 = new TopMission(['mission_name' => 'Training Mission', 'playtime_hours' => 50]);

        $data = [
            'current_players' => 3,
            'unique_players_24h' => 15,
            'total_playtime_hours_24h' => 30.0,
            'discord_members_24h' => 8,
            'unique_players_7d' => 60,
            'total_playtime_hours_7d' => 150.0,
            'discord_members_7d' => 40,
            'unique_players_30d' => 150,
            'total_playtime_hours_30d' => 750.0,
            'discord_members_30d' => 100,
            'top_missions' => [$mission1, $mission2],
        ];

        $stats = new ServerAttendanceStats($data);

        $topMissions = $stats->getTopMissions();
        $this->assertCount(2, $topMissions);
        $this->assertEquals('Operation Blue Flag', $topMissions[0]->getMissionName());
        $this->assertEquals(200, $topMissions[0]->getPlaytimeHours());
    }

    public function testAttendanceStatsWithTopModules(): void
    {
        $module1 = new TopModule([
            'module' => 'F-16C',
            'playtime_hours' => 500,
            'unique_players' => 50,
            'total_uses' => 200,
        ]);

        $data = [
            'current_players' => 8,
            'unique_players_24h' => 30,
            'total_playtime_hours_24h' => 60.0,
            'discord_members_24h' => 20,
            'unique_players_7d' => 100,
            'total_playtime_hours_7d' => 300.0,
            'discord_members_7d' => 70,
            'unique_players_30d' => 300,
            'total_playtime_hours_30d' => 1200.0,
            'discord_members_30d' => 180,
            'top_modules' => [$module1],
        ];

        $stats = new ServerAttendanceStats($data);

        $topModules = $stats->getTopModules();
        $this->assertCount(1, $topModules);
        $this->assertEquals('F-16C', $topModules[0]->getModule());
        $this->assertEquals(500, $topModules[0]->getPlaytimeHours());
        $this->assertEquals(50, $topModules[0]->getUniquePlayers());
        $this->assertEquals(200, $topModules[0]->getTotalUses());
    }

    public function testAttendanceStatsIsValid(): void
    {
        $stats = new ServerAttendanceStats([
            'current_players' => 0,
            'unique_players_24h' => 0,
            'total_playtime_hours_24h' => 0.0,
            'discord_members_24h' => 0,
            'unique_players_7d' => 0,
            'total_playtime_hours_7d' => 0.0,
            'discord_members_7d' => 0,
            'unique_players_30d' => 0,
            'total_playtime_hours_30d' => 0.0,
            'discord_members_30d' => 0,
        ]);

        $this->assertTrue($stats->valid());
    }
}
