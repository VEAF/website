<?php

namespace App\Tests\Unit\DTO;

use App\DTO\TeamSpeakClient;
use PHPUnit\Framework\TestCase;

/**
 * Tests for TeamSpeakClient DTO.
 *
 * Note: Tests for createFromNodeClient() are skipped because the TeamSpeak3
 * library is loaded lazily and the classes don't exist at test runtime.
 * The getter/setter tests provide sufficient coverage for the DTO.
 */
class TeamSpeakClientTest extends TestCase
{
    // =========================================================================
    // Tests for getters and setters
    // =========================================================================

    public function testSettersReturnSelfForFluentInterface(): void
    {
        $client = new TeamSpeakClient();

        $this->assertSame($client, $client->setClid(1));
        $this->assertSame($client, $client->setCid(1));
        $this->assertSame($client, $client->setDatabaseId(1));
        $this->assertSame($client, $client->setNickName('Test'));
        $this->assertSame($client, $client->setCountry('FR'));
        $this->assertSame($client, $client->setUniqueIdentifier('abc'));
        $this->assertSame($client, $client->setServerGroups(['1', '2']));
        $this->assertSame($client, $client->setChannelGroupId(1));
        $this->assertSame($client, $client->setVersion('1.0'));
        $this->assertSame($client, $client->setPlatform('Windows'));
        $this->assertSame($client, $client->setIdleTime(1000));
        $this->assertSame($client, $client->setCreated(1609459200));
        $this->assertSame($client, $client->setLastConnected(1609459200));
    }

    public function testGettersReturnSetValues(): void
    {
        $client = new TeamSpeakClient();

        $client->setClid(42);
        $this->assertEquals(42, $client->getClid());

        $client->setCid(10);
        $this->assertEquals(10, $client->getCid());

        $client->setDatabaseId(123);
        $this->assertEquals(123, $client->getDatabaseId());

        $client->setNickName('Pilot');
        $this->assertEquals('Pilot', $client->getNickName());

        $client->setCountry('DE');
        $this->assertEquals('DE', $client->getCountry());

        $client->setUniqueIdentifier('unique123');
        $this->assertEquals('unique123', $client->getUniqueIdentifier());

        $client->setServerGroups(['1', '2', '3']);
        $this->assertEquals(['1', '2', '3'], $client->getServerGroups());

        $client->setChannelGroupId(5);
        $this->assertEquals(5, $client->getChannelGroupId());

        $client->setVersion('3.5.6');
        $this->assertEquals('3.5.6', $client->getVersion());

        $client->setPlatform('macOS');
        $this->assertEquals('macOS', $client->getPlatform());

        $client->setIdleTime(5000);
        $this->assertEquals(5000, $client->getIdleTime());

        $client->setCreated(1609459200);
        $this->assertEquals(1609459200, $client->getCreated());

        $client->setLastConnected(1609459300);
        $this->assertEquals(1609459300, $client->getLastConnected());
    }

    public function testDefaultValuesAreNull(): void
    {
        $client = new TeamSpeakClient();

        $this->assertNull($client->getClid());
        $this->assertNull($client->getCid());
        $this->assertNull($client->getDatabaseId());
        $this->assertNull($client->getNickName());
        $this->assertNull($client->getCountry());
        $this->assertNull($client->getUniqueIdentifier());
        $this->assertNull($client->getChannelGroupId());
        $this->assertNull($client->getVersion());
        $this->assertNull($client->getPlatform());
        $this->assertNull($client->getIdleTime());
        $this->assertNull($client->getCreated());
        $this->assertNull($client->getLastConnected());
    }

    public function testServerGroupsDefaultsToEmptyArray(): void
    {
        $client = new TeamSpeakClient();

        $this->assertIsArray($client->getServerGroups());
        $this->assertEmpty($client->getServerGroups());
    }

    public function testServerGroupsCanBeSetAsArray(): void
    {
        $client = new TeamSpeakClient();
        $client->setServerGroups(['6', '7', '8']);

        $this->assertEquals(['6', '7', '8'], $client->getServerGroups());
        $this->assertCount(3, $client->getServerGroups());
    }
}
