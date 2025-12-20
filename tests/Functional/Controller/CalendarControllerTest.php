<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;

class CalendarControllerTest extends KernelTestCase
{
    private RouterInterface $router;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->router = static::getContainer()->get('router');
    }

    public function testCalendarUrlCanBeGeneratedWithoutMonthParameter(): void
    {
        $url = $this->router->generate('calendar');

        $this->assertEquals('/calendar/browse', $url);
    }

    public function testCalendarUrlCanBeGeneratedWithMonthParameter(): void
    {
        $url = $this->router->generate('calendar', ['month' => '2025-12']);

        $this->assertEquals('/calendar/browse/2025-12', $url);
    }

    public function testCalendarRouteMatchesWithoutMonth(): void
    {
        $match = $this->router->match('/calendar/browse');

        $this->assertEquals('calendar', $match['_route']);
        $this->assertNull($match['month']);
    }

    public function testCalendarRouteMatchesWithMonth(): void
    {
        $match = $this->router->match('/calendar/browse/2025-12');

        $this->assertEquals('calendar', $match['_route']);
        $this->assertEquals('2025-12', $match['month']);
    }
}
