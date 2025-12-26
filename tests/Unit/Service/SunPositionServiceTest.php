<?php

namespace App\Tests\Unit\Service;

use App\Service\SunPositionService;
use PHPUnit\Framework\TestCase;

class SunPositionServiceTest extends TestCase
{
    private SunPositionService $service;

    protected function setUp(): void
    {
        $this->service = new SunPositionService();
    }

    /**
     * @dataProvider theatreLatitudeProvider
     */
    public function testGetTheatreLatitudeReturnsCorrectValue(string $theatre, float $expectedLatitude): void
    {
        $latitude = $this->service->getTheatreLatitude($theatre);
        $this->assertEqualsWithDelta($expectedLatitude, $latitude, 0.1);
    }

    public function theatreLatitudeProvider(): array
    {
        return [
            'caucasus' => ['Caucasus', 43.6],
            'persiangulf' => ['PersianGulf', 25.0],
            'syria' => ['Syria', 35.0],
            'nevada' => ['Nevada', 36.0],
            'normandy' => ['Normandy', 49.0],
            'sinai' => ['Sinai', 30.0],
            'marianaislands' => ['MarianaIslands', 15.0],
            'southatlantic' => ['SouthAtlantic', -52.0],
            'kola' => ['Kola', 69.0],
            'afghanistan' => ['Afghanistan', 34.0],
        ];
    }

    public function testGetTheatreLatitudeIsCaseInsensitive(): void
    {
        $this->assertEqualsWithDelta(
            $this->service->getTheatreLatitude('CAUCASUS'),
            $this->service->getTheatreLatitude('caucasus'),
            0.01
        );

        $this->assertEqualsWithDelta(
            $this->service->getTheatreLatitude('PersianGulf'),
            $this->service->getTheatreLatitude('persiangulf'),
            0.01
        );
    }

    public function testGetTheatreLatitudeHandlesVariations(): void
    {
        // With spaces
        $this->assertEqualsWithDelta(25.0, $this->service->getTheatreLatitude('Persian Gulf'), 0.1);

        // With underscores
        $this->assertEqualsWithDelta(15.0, $this->service->getTheatreLatitude('Mariana_Islands'), 0.1);

        // With dashes
        $this->assertEqualsWithDelta(-52.0, $this->service->getTheatreLatitude('South-Atlantic'), 0.1);
    }

    public function testGetTheatreLatitudeReturnsDefaultForUnknown(): void
    {
        $latitude = $this->service->getTheatreLatitude('UnknownTheatre');
        $this->assertEqualsWithDelta(45.0, $latitude, 0.1);
    }

    /**
     * @dataProvider sunElevationProvider
     */
    public function testCalculateSunElevation(string $dateTime, float $latitude, float $minExpected, float $maxExpected): void
    {
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $elevation = $this->service->calculateSunElevation($dt, $latitude);

        $this->assertGreaterThanOrEqual($minExpected, $elevation);
        $this->assertLessThanOrEqual($maxExpected, $elevation);
    }

    public function sunElevationProvider(): array
    {
        return [
            // Midi en été au Caucase - soleil haut
            'caucasus_summer_noon' => ['2025-06-21 12:00:00', 43.6, 60.0, 75.0],
            // Minuit en été au Caucase - soleil sous l'horizon
            'caucasus_summer_midnight' => ['2025-06-21 00:00:00', 43.6, -70.0, -20.0],
            // Midi en hiver au Caucase - soleil plus bas
            'caucasus_winter_noon' => ['2025-12-21 12:00:00', 43.6, 15.0, 35.0],
            // Midi à Dubai (plus au sud) - soleil plus haut
            'dubai_summer_noon' => ['2025-06-21 12:00:00', 25.0, 80.0, 90.0],
            // Midi aux Falklands (hémisphère sud, été en décembre)
            'falklands_summer_noon' => ['2025-12-21 12:00:00', -52.0, 50.0, 70.0],
        ];
    }

    /**
     * @dataProvider sunStateProvider
     */
    public function testGetSunStateReturnsCorrectState(string $dateTime, string $theatre, string $expectedState): void
    {
        $result = $this->service->getSunState($dateTime, $theatre);

        $this->assertArrayHasKey('state', $result);
        $this->assertArrayHasKey('icon', $result);
        $this->assertArrayHasKey('color', $result);
        $this->assertArrayHasKey('tooltip', $result);
        $this->assertEquals($expectedState, $result['state']);
    }

    public function sunStateProvider(): array
    {
        return [
            // Cas clairs - jour
            'caucasus_midday_summer' => ['2025-06-21 12:00:00', 'Caucasus', 'day'],
            'caucasus_midday_winter' => ['2025-12-21 12:00:00', 'Caucasus', 'day'],
            'dubai_midday' => ['2025-06-21 12:00:00', 'PersianGulf', 'day'],
            'caucasus_morning' => ['2025-06-21 08:00:00', 'Caucasus', 'day'],
            'caucasus_afternoon' => ['2025-06-21 16:00:00', 'Caucasus', 'day'],

            // Cas clairs - nuit (minuit et heures très tardives/tôt)
            'caucasus_midnight_winter' => ['2025-12-21 00:00:00', 'Caucasus', 'night'],
            'caucasus_midnight_summer' => ['2025-06-21 00:00:00', 'Caucasus', 'night'],
            'dubai_midnight' => ['2025-06-21 00:00:00', 'PersianGulf', 'night'],
            'dubai_late_night' => ['2025-06-21 03:00:00', 'PersianGulf', 'night'],

            // Aube (matin, élévation entre -18 et -6) - fenêtre très courte
            'caucasus_dawn_summer' => ['2025-06-21 03:30:00', 'Caucasus', 'dawn'],
            'dubai_dawn' => ['2025-06-21 04:30:00', 'PersianGulf', 'dawn'],

            // Crépuscule (soir, élévation entre -18 et -6) - fenêtre très courte
            'caucasus_dusk_summer' => ['2025-06-21 21:00:00', 'Caucasus', 'dusk'],
            'dubai_dusk' => ['2025-06-21 20:00:00', 'PersianGulf', 'dusk'],
        ];
    }

    public function testGetSunStateReturnsCorrectIcons(): void
    {
        $dayResult = $this->service->getSunState('2025-06-21 12:00:00', 'Caucasus');
        $this->assertEquals('fas fa-sun', $dayResult['icon']);
        $this->assertEquals('#ffc107', $dayResult['color']);
        $this->assertEquals('Jour', $dayResult['tooltip']);

        $nightResult = $this->service->getSunState('2025-06-21 00:00:00', 'Caucasus');
        $this->assertEquals('fas fa-moon', $nightResult['icon']);
        $this->assertEquals('#6c757d', $nightResult['color']);
        $this->assertEquals('Nuit', $nightResult['tooltip']);

        // Aube très tôt le matin (élévation entre -18 et -6)
        $dawnResult = $this->service->getSunState('2025-06-21 03:30:00', 'Caucasus');
        $this->assertEquals('fas fa-cloud-sun', $dawnResult['icon']);
        $this->assertEquals('#fd7e14', $dawnResult['color']);
        $this->assertEquals('Aube', $dawnResult['tooltip']);

        // Crépuscule tard le soir
        $duskResult = $this->service->getSunState('2025-06-21 21:00:00', 'Caucasus');
        $this->assertEquals('fas fa-cloud-sun', $duskResult['icon']);
        $this->assertEquals('#fd7e14', $duskResult['color']);
        $this->assertEquals('Crépuscule', $duskResult['tooltip']);
    }

    public function testGetSunStateHandlesInvalidDateTime(): void
    {
        $result = $this->service->getSunState('invalid-date', 'Caucasus');

        // Should return default (day) state
        $this->assertEquals('day', $result['state']);
    }

    public function testGetSunStateHandlesAlternativeDateFormat(): void
    {
        // Format without seconds
        $result = $this->service->getSunState('2025-06-21 12:00', 'Caucasus');
        $this->assertEquals('day', $result['state']);
    }

    /**
     * Test spécifique pour Kola (latitude extrême avec soleil de minuit)
     */
    public function testKolaMidnightSunInSummer(): void
    {
        // En été, à Kola (69°N), le soleil peut rester au-dessus de l'horizon à minuit
        $result = $this->service->getSunState('2025-06-21 00:00:00', 'Kola');

        // Le soleil de minuit signifie qu'il peut être jour ou aube même à minuit
        $this->assertContains($result['state'], ['day', 'dawn', 'dusk']);
    }

    /**
     * Test pour les Falklands (hémisphère sud)
     */
    public function testSouthAtlanticSeasons(): void
    {
        // Décembre = été dans l'hémisphère sud
        $summerResult = $this->service->getSunState('2025-12-21 12:00:00', 'SouthAtlantic');
        $this->assertEquals('day', $summerResult['state']);

        // Juin = hiver dans l'hémisphère sud (jours courts mais toujours jour à midi)
        $winterResult = $this->service->getSunState('2025-06-21 12:00:00', 'SouthAtlantic');
        $this->assertEquals('day', $winterResult['state']);
    }
}
