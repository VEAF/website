<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;

class DcsBotControllerTest extends KernelTestCase
{
    private RouterInterface $router;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->router = static::getContainer()->get('router');
    }

    public function testDcsBotStatsRouteCanBeGenerated(): void
    {
        $url = $this->router->generate('dcsbot_stats');

        $this->assertEquals('/stats/dcs', $url);
    }

    public function testDcsBotServerRouteCanBeGenerated(): void
    {
        $url = $this->router->generate('dcsbot_server', ['serverName' => 'public']);

        $this->assertEquals('/stats/dcs/public', $url);
    }

    public function testDcsBotServerRouteMatchesWithServerName(): void
    {
        $match = $this->router->match('/stats/dcs/public');

        $this->assertEquals('dcsbot_server', $match['_route']);
        $this->assertEquals('public', $match['serverName']);
    }

    public function testDcsBotServerRouteMatchesWithDifferentServerNames(): void
    {
        $testCases = [
            'public' => 'public',
            'training' => 'training',
            'DCS-Server-1' => 'DCS-Server-1',
        ];

        foreach ($testCases as $serverName => $expected) {
            $match = $this->router->match('/stats/dcs/'.$serverName);

            $this->assertEquals('dcsbot_server', $match['_route']);
            $this->assertEquals($expected, $match['serverName']);
        }
    }
}
