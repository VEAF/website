<?php

namespace App\Tests\Unit\Service;

use App\DTO\TeamSpeakChannel;
use App\DTO\TeamSpeakClient;
use App\Service\TeamSpeak3Client;
use App\Service\TeamSpeak3ClientCache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class TeamSpeak3ClientCacheTest extends TestCase
{
    /** @var TeamSpeak3Client|MockObject */
    private $ts3Client;
    /** @var ArrayAdapter */
    private $cacheAdapter;
    /** @var LoggerInterface|MockObject */
    private $logger;
    /** @var TeamSpeak3ClientCache */
    private $service;

    protected function setUp(): void
    {
        $this->ts3Client = $this->createMock(TeamSpeak3Client::class);
        $this->cacheAdapter = new ArrayAdapter();
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new TeamSpeak3ClientCache(
            $this->ts3Client,
            $this->cacheAdapter,
            $this->logger
        );
    }

    // =========================================================================
    // Tests for getTeamSpeakClient()
    // =========================================================================

    public function testGetTeamSpeakClientReturnsClient(): void
    {
        $result = $this->service->getTeamSpeakClient();

        $this->assertSame($this->ts3Client, $result);
    }

    // =========================================================================
    // Tests for getClients()
    // =========================================================================

    public function testGetClientsReturnsNullWhenCacheEmpty(): void
    {
        $result = $this->service->getClients();

        $this->assertNull($result);
    }

    public function testGetClientsReturnsCachedDataAfterPut(): void
    {
        $clients = [$this->createClient('Player1'), $this->createClient('Player2')];
        $this->ts3Client->method('getClients')->willReturn($clients);

        $this->service->putClients();
        $result = $this->service->getClients();

        $this->assertCount(2, $result);
    }

    // =========================================================================
    // Tests for putClients()
    // =========================================================================

    public function testPutClientsFetchesAndCachesClients(): void
    {
        $clients = [$this->createClient('Player1'), $this->createClient('Player2')];

        $this->ts3Client
            ->expects($this->once())
            ->method('getClients')
            ->willReturn($clients);

        $this->service->putClients();

        // Verify clients are cached
        $cachedClients = $this->service->getClients();
        $this->assertCount(2, $cachedClients);

        // Verify count is cached
        $cachedCount = $this->service->countClients();
        $this->assertEquals(2, $cachedCount);
    }

    public function testPutClientsCachesEmptyArrayOnException(): void
    {
        $this->ts3Client
            ->method('getClients')
            ->willThrowException(new \Exception('Connection failed'));

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($this->stringContains('error reading teamspeak clients'));

        $this->service->putClients();

        // Verify empty array is cached
        $cachedClients = $this->service->getClients();
        $this->assertIsArray($cachedClients);
        $this->assertEmpty($cachedClients);

        // Verify count is 0
        $cachedCount = $this->service->countClients();
        $this->assertEquals(0, $cachedCount);
    }

    // =========================================================================
    // Tests for getChannels()
    // =========================================================================

    public function testGetChannelsReturnsNullWhenCacheEmpty(): void
    {
        $result = $this->service->getChannels();

        $this->assertNull($result);
    }

    public function testGetChannelsReturnsCachedDataAfterPut(): void
    {
        $channels = [$this->createChannel('Lobby'), $this->createChannel('Flight')];
        $this->ts3Client->method('getChannels')->willReturn($channels);

        $this->service->putChannels();
        $result = $this->service->getChannels();

        $this->assertCount(2, $result);
    }

    // =========================================================================
    // Tests for putChannels()
    // =========================================================================

    public function testPutChannelsFetchesAndCachesChannels(): void
    {
        $channels = [$this->createChannel('Lobby'), $this->createChannel('Flight')];

        $this->ts3Client
            ->expects($this->once())
            ->method('getChannels')
            ->willReturn($channels);

        $this->service->putChannels();

        // Verify channels are cached
        $cachedChannels = $this->service->getChannels();
        $this->assertCount(2, $cachedChannels);
    }

    public function testPutChannelsCachesEmptyArrayOnException(): void
    {
        $this->ts3Client
            ->method('getChannels')
            ->willThrowException(new \Exception('Connection failed'));

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->service->putChannels();

        // Verify empty array is cached
        $cachedChannels = $this->service->getChannels();
        $this->assertIsArray($cachedChannels);
        $this->assertEmpty($cachedChannels);
    }

    public function testPutChannelsFillsClientsInChannelsWhenClientsLoaded(): void
    {
        // First, load clients via putClients
        $client1 = $this->createClient('Player1');
        $client1->setCid(1);
        $client2 = $this->createClient('Player2');
        $client2->setCid(2);
        $clients = [$client1, $client2];

        $this->ts3Client->method('getClients')->willReturn($clients);

        // Now prepare channels
        $channel1 = $this->createChannel('Lobby');
        $channel1->setCid(1);
        $channel2 = $this->createChannel('Flight');
        $channel2->setCid(2);
        $channels = [$channel1, $channel2];

        $this->ts3Client->method('getChannels')->willReturn($channels);

        // Call putClients first to load clients into the service
        $this->service->putClients();

        // Now call putChannels - it should fill clients into channels
        $this->service->putChannels();

        // Verify clients were added to channels
        $this->assertCount(1, $channel1->getClients());
        $this->assertCount(1, $channel2->getClients());
        $this->assertSame($client1, $channel1->getClients()[0]);
        $this->assertSame($client2, $channel2->getClients()[0]);
    }

    // =========================================================================
    // Tests for countClients()
    // =========================================================================

    public function testCountClientsReturnsNullWhenNotCached(): void
    {
        $result = $this->service->countClients();

        $this->assertNull($result);
    }

    public function testCountClientsReturnsCachedCount(): void
    {
        $clients = [
            $this->createClient('Player1'),
            $this->createClient('Player2'),
            $this->createClient('Player3'),
        ];
        $this->ts3Client->method('getClients')->willReturn($clients);

        $this->service->putClients();

        $result = $this->service->countClients();

        $this->assertEquals(3, $result);
    }

    // =========================================================================
    // Tests for cache configuration
    // =========================================================================

    public function testCacheExpirationConstantIs120Seconds(): void
    {
        $this->assertEquals(120, TeamSpeak3ClientCache::CACHE_EXPIRES);
    }

    public function testCachePrefixIsTeamspeak3(): void
    {
        $this->assertEquals('teamspeak3', TeamSpeak3ClientCache::CACHE_PREFIX);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function createClient(string $nickname): TeamSpeakClient
    {
        $client = new TeamSpeakClient();
        $client->setNickName($nickname);

        return $client;
    }

    private function createChannel(string $name): TeamSpeakChannel
    {
        $channel = new TeamSpeakChannel();
        $channel->setName($name);

        return $channel;
    }
}
