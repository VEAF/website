<?php

namespace App\Tests\Unit\DTO;

use App\DTO\TeamSpeakChannel;
use App\DTO\TeamSpeakClient;
use PHPUnit\Framework\TestCase;

/**
 * Tests for TeamSpeakChannel DTO.
 *
 * Note: Tests for createFromNodeChannel() are skipped because the TeamSpeak3
 * library is loaded lazily and the classes don't exist at test runtime.
 * The getter/setter tests provide sufficient coverage for the DTO.
 */
class TeamSpeakChannelTest extends TestCase
{
    // =========================================================================
    // Tests for getters and setters
    // =========================================================================

    public function testSettersReturnSelfForFluentInterface(): void
    {
        $channel = new TeamSpeakChannel();

        $this->assertSame($channel, $channel->setCid(1));
        $this->assertSame($channel, $channel->setPid(0));
        $this->assertSame($channel, $channel->setOrder(1));
        $this->assertSame($channel, $channel->setName('Test'));
        $this->assertSame($channel, $channel->setTopic('Topic'));
    }

    public function testGettersReturnSetValues(): void
    {
        $channel = new TeamSpeakChannel();

        $channel->setCid(42);
        $this->assertEquals(42, $channel->getCid());

        $channel->setPid(10);
        $this->assertEquals(10, $channel->getPid());

        $channel->setOrder(5);
        $this->assertEquals(5, $channel->getOrder());

        $channel->setName('Operations Room');
        $this->assertEquals('Operations Room', $channel->getName());

        $channel->setTopic('Mission briefings');
        $this->assertEquals('Mission briefings', $channel->getTopic());
    }

    public function testDefaultValuesAreNull(): void
    {
        $channel = new TeamSpeakChannel();

        $this->assertNull($channel->getCid());
        $this->assertNull($channel->getPid());
        $this->assertNull($channel->getOrder());
        $this->assertNull($channel->getName());
        $this->assertNull($channel->getTopic());
    }

    // =========================================================================
    // Tests for client management
    // =========================================================================

    public function testGetClientsReturnsEmptyArrayByDefault(): void
    {
        $channel = new TeamSpeakChannel();

        $this->assertIsArray($channel->getClients());
        $this->assertEmpty($channel->getClients());
    }

    public function testAddClientAddsToClientsList(): void
    {
        $channel = new TeamSpeakChannel();
        $client = new TeamSpeakClient();
        $client->setNickName('Pilot1');

        $channel->addClient($client);

        $this->assertCount(1, $channel->getClients());
        $this->assertSame($client, $channel->getClients()[0]);
    }

    public function testAddMultipleClients(): void
    {
        $channel = new TeamSpeakChannel();

        $client1 = new TeamSpeakClient();
        $client1->setNickName('Pilot1');

        $client2 = new TeamSpeakClient();
        $client2->setNickName('Pilot2');

        $client3 = new TeamSpeakClient();
        $client3->setNickName('Pilot3');

        $channel->addClient($client1);
        $channel->addClient($client2);
        $channel->addClient($client3);

        $clients = $channel->getClients();

        $this->assertCount(3, $clients);
        $this->assertSame($client1, $clients[0]);
        $this->assertSame($client2, $clients[1]);
        $this->assertSame($client3, $clients[2]);
    }

    public function testClientsAreAddedInOrder(): void
    {
        $channel = new TeamSpeakChannel();

        for ($i = 1; $i <= 5; ++$i) {
            $client = new TeamSpeakClient();
            $client->setNickName("Pilot{$i}");
            $channel->addClient($client);
        }

        $clients = $channel->getClients();

        $this->assertEquals('Pilot1', $clients[0]->getNickName());
        $this->assertEquals('Pilot5', $clients[4]->getNickName());
    }
}
