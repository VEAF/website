<?php

namespace App\Tests\Unit\Command;

use App\Command\TeamSpeakScanCommand;
use App\Service\TeamSpeak3ClientCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class TeamSpeakScanCommandTest extends TestCase
{
    private TeamSpeak3ClientCache $clientCache;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->clientCache = $this->createMock(TeamSpeak3ClientCache::class);

        $command = new TeamSpeakScanCommand($this->clientCache);

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteCallsPutClientsAndPutChannels(): void
    {
        $this->clientCache
            ->expects($this->once())
            ->method('putClients');

        $this->clientCache
            ->expects($this->once())
            ->method('putChannels');

        $this->commandTester->execute([]);
    }

    public function testExecuteReturnsSuccessCode(): void
    {
        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(0, $exitCode);
    }

    public function testExecuteOutputsProgressMessages(): void
    {
        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('scanning team speak server', $output);
        $this->assertStringContainsString('team speak server scan done', $output);
    }
}
