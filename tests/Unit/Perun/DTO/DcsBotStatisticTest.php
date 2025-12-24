<?php

namespace App\Tests\Unit\Perun\DTO;

use App\Perun\DTO\DcsBotStatistic;
use PHPUnit\Framework\TestCase;

/**
 * Tests for DcsBotStatistic DTO.
 */
class DcsBotStatisticTest extends TestCase
{
    public function testFromRowCreatesValidDto(): void
    {
        $row = [
            'mission_id' => 42,
            'player_ucid' => 'abc123def456',
            'slot' => 'FA-18C_hornet',
            'side' => 2,
            'kills' => 5,
            'pvp' => 2,
            'deaths' => 1,
            'ejections' => 0,
            'crashes' => 1,
            'teamkills' => 0,
            'kills_planes' => 3,
            'kills_helicopters' => 1,
            'kills_ships' => 0,
            'kills_sams' => 1,
            'kills_ground' => 2,
            'takeoffs' => 3,
            'landings' => 2,
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => '2024-12-24 11:30:00',
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals(42, $dto->getMissionId());
        $this->assertEquals('abc123def456', $dto->getPlayerUcid());
        $this->assertEquals('FA-18C_hornet', $dto->getSlot());
        $this->assertEquals(2, $dto->getSide());
        $this->assertEquals(5, $dto->getKills());
        $this->assertEquals(2, $dto->getPvp());
        $this->assertEquals(1, $dto->getDeaths());
        $this->assertEquals(0, $dto->getEjections());
        $this->assertEquals(1, $dto->getCrashes());
        $this->assertEquals(0, $dto->getTeamkills());
        $this->assertEquals(3, $dto->getKillsPlanes());
        $this->assertEquals(1, $dto->getKillsHelicopters());
        $this->assertEquals(0, $dto->getKillsShips());
        $this->assertEquals(1, $dto->getKillsSams());
        $this->assertEquals(2, $dto->getKillsGround());
        $this->assertEquals(3, $dto->getTakeoffs());
        $this->assertEquals(2, $dto->getLandings());
    }

    public function testFromRowHandlesMissingOptionalFields(): void
    {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => null,
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals(0, $dto->getSide());
        $this->assertEquals(0, $dto->getKills());
        $this->assertEquals(0, $dto->getPvp());
        $this->assertEquals(0, $dto->getDeaths());
        $this->assertEquals(0, $dto->getEjections());
        $this->assertEquals(0, $dto->getCrashes());
        $this->assertEquals(0, $dto->getTeamkills());
        $this->assertNull($dto->getHopOff());
    }

    public function testGetSessionDurationReturnsCorrectSeconds(): void
    {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => '2024-12-24 11:30:00', // 1h30 = 5400 seconds
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals(5400, $dto->getSessionDuration());
    }

    public function testGetSessionDurationReturnsZeroForNullHopOff(): void
    {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => null,
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals(0, $dto->getSessionDuration());
    }

    public function testGetSessionDurationReturnsZeroForSameHopOnAndHopOff(): void
    {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => '2024-12-24 10:00:00',
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals(0, $dto->getSessionDuration());
    }

    public function testHopOnAndHopOffAreDateTimeObjects(): void
    {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => '2024-12-24 10:00:00',
            'hop_off' => '2024-12-24 11:30:00',
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertInstanceOf(\DateTime::class, $dto->getHopOn());
        $this->assertInstanceOf(\DateTime::class, $dto->getHopOff());
        $this->assertEquals('2024-12-24 10:00:00', $dto->getHopOn()->format('Y-m-d H:i:s'));
        $this->assertEquals('2024-12-24 11:30:00', $dto->getHopOff()->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider sessionDurationDataProvider
     */
    public function testGetSessionDurationWithVariousDurations(
        string $hopOn,
        string $hopOff,
        int $expectedSeconds
    ): void {
        $row = [
            'mission_id' => 1,
            'player_ucid' => 'ucid123',
            'slot' => 'A-10C_2',
            'hop_on' => $hopOn,
            'hop_off' => $hopOff,
        ];

        $dto = DcsBotStatistic::fromRow($row);

        $this->assertEquals($expectedSeconds, $dto->getSessionDuration());
    }

    public function sessionDurationDataProvider(): array
    {
        return [
            'one minute' => ['2024-12-24 10:00:00', '2024-12-24 10:01:00', 60],
            'one hour' => ['2024-12-24 10:00:00', '2024-12-24 11:00:00', 3600],
            'two hours thirty minutes' => ['2024-12-24 10:00:00', '2024-12-24 12:30:00', 9000],
            'crossing midnight' => ['2024-12-24 23:00:00', '2024-12-25 01:00:00', 7200],
        ];
    }
}
