<?php

namespace App\Tests\Unit\Service;

use App\Perun\DTO\Position;
use App\Service\ProjectionService;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour ProjectionService.
 *
 * Ce service convertit les coordonnées XY (DCS World) en coordonnées lat/long.
 */
class ProjectionServiceTest extends TestCase
{
    private ProjectionService $service;

    protected function setUp(): void
    {
        $this->service = new ProjectionService();
    }

    /**
     * @dataProvider supportedTheatresProvider
     */
    public function testXyToLLForSupportedTheatres(string $theatre): void
    {
        $position = new Position(0, 0);

        $result = $this->service->xyToLL($position, $theatre);

        // Vérifie que le résultat est une Position valide
        $this->assertInstanceOf(Position::class, $result);
        $this->assertIsFloat($result->getX());
        $this->assertIsFloat($result->getY());
    }

    public function supportedTheatresProvider(): array
    {
        return [
            'caucasus' => ['caucasus'],
            'persiangulf' => ['persiangulf'],
            'syria' => ['syria'],
            'marianaislands' => ['marianaislands'],
        ];
    }

    public function testTheatreNameIsCaseInsensitive(): void
    {
        $position = new Position(1000, 2000);

        $result1 = $this->service->xyToLL($position, 'CAUCASUS');
        $result2 = $this->service->xyToLL($position, 'Caucasus');
        $result3 = $this->service->xyToLL($position, 'caucasus');

        // Tous les résultats doivent être identiques
        $this->assertEqualsWithDelta($result1->getX(), $result2->getX(), 0.0001);
        $this->assertEqualsWithDelta($result2->getX(), $result3->getX(), 0.0001);
        $this->assertEqualsWithDelta($result1->getY(), $result2->getY(), 0.0001);
        $this->assertEqualsWithDelta($result2->getY(), $result3->getY(), 0.0001);
    }

    public function testUnsupportedTheatreThrowsException(): void
    {
        $position = new Position(0, 0);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/unsupported theatre/');

        $this->service->xyToLL($position, 'unknown_theatre');
    }

    public function testProjectionIsCached(): void
    {
        $position1 = new Position(1000, 2000);
        $position2 = new Position(3000, 4000);

        // Deux appels avec le même théâtre
        $result1 = $this->service->xyToLL($position1, 'caucasus');
        $result2 = $this->service->xyToLL($position2, 'caucasus');

        // Les deux doivent fonctionner (la projection est mise en cache)
        $this->assertInstanceOf(Position::class, $result1);
        $this->assertInstanceOf(Position::class, $result2);

        // Les résultats doivent être différents car les positions sont différentes
        $this->assertNotEquals($result1->getX(), $result2->getX());
    }

    /**
     * Test des coordonnées connues pour Persian Gulf.
     * Origin: lat=26.1718, lon=56.2419
     */
    public function testPersianGulfOriginCoordinates(): void
    {
        $position = new Position(0, 0);

        $result = $this->service->xyToLL($position, 'persiangulf');

        // À l'origine (0,0), on doit obtenir les coordonnées de référence
        $this->assertEqualsWithDelta(56.2419, $result->getX(), 0.01, 'Longitude at origin');
        $this->assertEqualsWithDelta(26.1718, $result->getY(), 0.01, 'Latitude at origin');
    }

    /**
     * Test des coordonnées connues pour Syria.
     * Origin: lat=35.21917, lon=35.9055
     */
    public function testSyriaOriginCoordinates(): void
    {
        $position = new Position(0, 0);

        $result = $this->service->xyToLL($position, 'syria');

        $this->assertEqualsWithDelta(35.9055, $result->getX(), 0.01, 'Longitude at origin');
        $this->assertEqualsWithDelta(35.21917, $result->getY(), 0.01, 'Latitude at origin');
    }

    /**
     * Test des coordonnées connues pour Mariana Islands.
     * Origin: lat=13.485, lon=144.7533
     */
    public function testMarianaIslandsOriginCoordinates(): void
    {
        $position = new Position(0, 0);

        $result = $this->service->xyToLL($position, 'marianaislands');

        $this->assertEqualsWithDelta(144.7533, $result->getX(), 0.01, 'Longitude at origin');
        $this->assertEqualsWithDelta(13.485, $result->getY(), 0.01, 'Latitude at origin');
    }

    /**
     * Test que les coordonnées X/Y non nulles produisent des résultats différents.
     */
    public function testNonZeroCoordinatesProduceDifferentResults(): void
    {
        $origin = new Position(0, 0);
        $offset = new Position(10000, 10000); // 10km offset

        $resultOrigin = $this->service->xyToLL($origin, 'caucasus');
        $resultOffset = $this->service->xyToLL($offset, 'caucasus');

        // Les résultats doivent être différents
        $this->assertNotEquals($resultOrigin->getX(), $resultOffset->getX());
        $this->assertNotEquals($resultOrigin->getY(), $resultOffset->getY());
    }
}
