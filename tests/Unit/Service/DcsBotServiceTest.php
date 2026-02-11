<?php

namespace App\Tests\Unit\Service;

use App\Service\DcsBotService;
use DcsServerBot\Api\InfoApi;
use DcsServerBot\ApiException;
use DcsServerBot\Model\PlayerEntry;
use DcsServerBot\Model\ServerInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DcsBotServiceTest extends TestCase
{
    /** @var InfoApi&MockObject */
    private InfoApi $infoApi;
    private ArrayAdapter $cacheAdapter;
    /** @var LoggerInterface&MockObject */
    private LoggerInterface $logger;
    private DcsBotService $service;

    protected function setUp(): void
    {
        $this->infoApi = $this->createMock(InfoApi::class);
        $this->cacheAdapter = new ArrayAdapter();
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new DcsBotService(
            $this->infoApi,
            $this->cacheAdapter,
            $this->logger,
        );
    }

    public function testGetActivePlayersReturnsNullWhenApiUnavailable(): void
    {
        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willThrowException(new ApiException('API down'));

        $this->assertNull($this->service->getActivePlayers());
    }

    public function testGetActivePlayersCountsOnlyRunningServers(): void
    {
        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willReturn([
                $this->createServerInfo('Running', 3),
                $this->createServerInfo('Paused', 2),
                $this->createServerInfo('Shutdown', 0),
            ]);

        $this->assertSame(3, $this->service->getActivePlayers());
    }

    public function testGetActivePlayersReturnsZeroWhenNoRunningServers(): void
    {
        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willReturn([
                $this->createServerInfo('Paused', 5),
            ]);

        $this->assertSame(0, $this->service->getActivePlayers());
    }

    public function testGetActivePlayersSumsAcrossMultipleRunningServers(): void
    {
        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willReturn([
                $this->createServerInfo('Running', 2),
                $this->createServerInfo('Running', 4),
            ]);

        $this->assertSame(6, $this->service->getActivePlayers());
    }

    public function testGetActivePlayersHandlesNullPlayersArray(): void
    {
        $server = new ServerInfo([
            'name' => 'TestServer',
            'status' => 'Running',
            'address' => '1.2.3.4:10308',
            'password' => '',
        ]);

        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willReturn([$server]);

        $this->assertSame(0, $this->service->getActivePlayers());
    }

    public function testGetActivePlayersReturnsZeroForEmptyServerList(): void
    {
        $this->infoApi
            ->method('serversServerapiServersGet')
            ->willReturn([]);

        $this->assertSame(0, $this->service->getActivePlayers());
    }

    private function createServerInfo(string $status, int $playerCount): ServerInfo
    {
        $players = [];
        for ($i = 0; $i < $playerCount; $i++) {
            $players[] = new PlayerEntry([
                'nick' => 'Player'.$i,
                'side' => 'Blue',
                'unit_type' => 'F-16C',
                'callsign' => 'Viper'.$i,
                'radios' => [],
            ]);
        }

        return new ServerInfo([
            'name' => 'TestServer-'.$status,
            'status' => $status,
            'address' => '1.2.3.4:10308',
            'password' => '',
            'players' => $players,
        ]);
    }
}
