<?php

namespace App\Tests\Unit\Service;

use App\Service\WeatherConversionService;
use PHPUnit\Framework\TestCase;

class WeatherConversionServiceTest extends TestCase
{
    private WeatherConversionService $service;

    protected function setUp(): void
    {
        $this->service = new WeatherConversionService();
    }

    /**
     * @dataProvider temperatureProvider
     */
    public function testCelsiusToFahrenheit(float $celsius, float $expectedFahrenheit): void
    {
        $result = $this->service->celsiusToFahrenheit($celsius);

        $this->assertEqualsWithDelta($expectedFahrenheit, $result, 0.1);
    }

    public function temperatureProvider(): array
    {
        return [
            'freezing point' => [0, 32],
            'boiling point' => [100, 212],
            'negative temperature' => [-40, -40], // Point where C and F are equal
            'room temperature' => [20, 68],
            'body temperature' => [37, 98.6],
        ];
    }

    /**
     * @dataProvider pressureMmHgToHpaProvider
     */
    public function testMmHgToHpa(float $mmHg, float $expectedHpa): void
    {
        $result = $this->service->mmHgToHpa($mmHg);

        $this->assertEqualsWithDelta($expectedHpa, $result, 0.5);
    }

    public function pressureMmHgToHpaProvider(): array
    {
        return [
            'standard pressure' => [760, 1013.25],
            'low pressure' => [740, 986.58],
            'high pressure' => [780, 1039.92],
        ];
    }

    /**
     * @dataProvider pressureMmHgToInHgProvider
     */
    public function testMmHgToInHg(float $mmHg, float $expectedInHg): void
    {
        $result = $this->service->mmHgToInHg($mmHg);

        $this->assertEqualsWithDelta($expectedInHg, $result, 0.01);
    }

    public function pressureMmHgToInHgProvider(): array
    {
        return [
            'standard pressure' => [760, 29.92],
            'low pressure' => [740, 29.13],
            'high pressure' => [780, 30.71],
        ];
    }

    /**
     * @dataProvider windSpeedKnotsProvider
     */
    public function testMsToKnots(float $ms, float $expectedKnots): void
    {
        $result = $this->service->msToKnots($ms);

        $this->assertEqualsWithDelta($expectedKnots, $result, 0.1);
    }

    public function windSpeedKnotsProvider(): array
    {
        return [
            'calm' => [0, 0],
            'light breeze' => [5, 9.72],
            'strong wind' => [15, 29.16],
            'storm' => [30, 58.32],
        ];
    }

    /**
     * @dataProvider windSpeedKmhProvider
     */
    public function testMsToKmh(float $ms, float $expectedKmh): void
    {
        $result = $this->service->msToKmh($ms);

        $this->assertEqualsWithDelta($expectedKmh, $result, 0.1);
    }

    public function windSpeedKmhProvider(): array
    {
        return [
            'calm' => [0, 0],
            'light breeze' => [5, 18],
            'strong wind' => [15, 54],
            'storm' => [30, 108],
        ];
    }

    /**
     * @dataProvider windDirectionProvider
     */
    public function testWindDirectionToHeading(int $fromDirection, int $expectedHeading): void
    {
        $result = $this->service->windDirectionToHeading($fromDirection);

        $this->assertEquals($expectedHeading, $result);
    }

    public function windDirectionProvider(): array
    {
        return [
            'north wind blows south' => [0, 180],
            'south wind blows north' => [180, 0],
            'east wind blows west' => [90, 270],
            'west wind blows east' => [270, 90],
            'northwest wind' => [315, 135],
            'full circle' => [360, 180],
        ];
    }

    /**
     * @dataProvider visibilityProvider
     */
    public function testMetersToKm(int $meters, float $expectedKm): void
    {
        $result = $this->service->metersToKm($meters);

        $this->assertEqualsWithDelta($expectedKm, $result, 0.01);
    }

    public function visibilityProvider(): array
    {
        return [
            'one kilometer' => [1000, 1.0],
            'ten kilometers' => [10000, 10.0],
            'low visibility' => [500, 0.5],
            'very low visibility' => [100, 0.1],
        ];
    }
}
