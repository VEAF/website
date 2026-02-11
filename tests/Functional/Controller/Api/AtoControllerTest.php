<?php

namespace App\Tests\Functional\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AtoControllerTest extends KernelTestCase
{
    private RouterInterface $router;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->router = static::getContainer()->get('router');
    }

    public function testAtoDataRouteCanBeGenerated(): void
    {
        $url = $this->router->generate('api_ato_data', ['event' => 1]);

        $this->assertEquals('/calendar/api/ato/1/data', $url);
    }

    public function testAtoSaveRouteCanBeGenerated(): void
    {
        $url = $this->router->generate('api_ato_save', ['event' => 1]);

        $this->assertEquals('/calendar/api/ato/1/save', $url);
    }

    public function testAtoDataRouteMatchesCorrectly(): void
    {
        $match = $this->router->match('/calendar/api/ato/42/data');

        $this->assertEquals('api_ato_data', $match['_route']);
        $this->assertEquals('42', $match['event']);
    }

    public function testAtoSaveRouteMatchesWithPostMethod(): void
    {
        $context = $this->router->getContext();
        $context->setMethod('POST');
        $match = $this->router->match('/calendar/api/ato/42/save');
        $context->setMethod('GET');

        $this->assertEquals('api_ato_save', $match['_route']);
        $this->assertEquals('42', $match['event']);
    }

    public function testEditAtoRouteStillExists(): void
    {
        $url = $this->router->generate('calendar_edit_ato', ['event' => 1]);

        $this->assertEquals('/calendar/edit/1/ato', $url);
    }
}
