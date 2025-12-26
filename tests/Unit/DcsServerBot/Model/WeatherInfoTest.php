<?php

namespace App\Tests\Unit\DcsServerBot\Model;

use DcsServerBot\Model\WeatherInfo;
use PHPUnit\Framework\TestCase;

class WeatherInfoTest extends TestCase
{
    public function testCreateWeatherInfoWithAllFields(): void
    {
        $data = [
            'temperature' => 15.5,
            'wind_speed' => 5.2,
            'wind_direction' => 270,
            'pressure' => 760.0,
            'visibility' => 9999,
            'clouds_base' => 8000,
            'clouds_density' => 4,
            'precipitation' => 0,
            'fog_enabled' => false,
            'fog_visibility' => null,
            'dust_enabled' => false,
            'dust_visibility' => null,
        ];

        $weather = new WeatherInfo($data);

        $this->assertEquals(15.5, $weather->getTemperature());
        $this->assertEquals(5.2, $weather->getWindSpeed());
        $this->assertEquals(270, $weather->getWindDirection());
        $this->assertEquals(760.0, $weather->getPressure());
        $this->assertEquals(9999, $weather->getVisibility());
        $this->assertEquals(8000, $weather->getCloudsBase());
        $this->assertEquals(4, $weather->getCloudsDensity());
        $this->assertEquals(0, $weather->getPrecipitation());
        $this->assertFalse($weather->getFogEnabled());
        $this->assertNull($weather->getFogVisibility());
        $this->assertFalse($weather->getDustEnabled());
        $this->assertNull($weather->getDustVisibility());
    }

    public function testCreateWeatherInfoWithNullValues(): void
    {
        $weather = new WeatherInfo();

        $this->assertNull($weather->getTemperature());
        $this->assertNull($weather->getWindSpeed());
        $this->assertNull($weather->getWindDirection());
        $this->assertNull($weather->getPressure());
        $this->assertNull($weather->getVisibility());
    }

    public function testSettersReturnSelf(): void
    {
        $weather = new WeatherInfo();

        $result = $weather->setTemperature(20.0);

        $this->assertSame($weather, $result);
    }

    public function testWeatherInfoWithFogEnabled(): void
    {
        $data = [
            'temperature' => 10.0,
            'fog_enabled' => true,
            'fog_visibility' => 500,
        ];

        $weather = new WeatherInfo($data);

        $this->assertTrue($weather->getFogEnabled());
        $this->assertEquals(500, $weather->getFogVisibility());
    }

    public function testWeatherInfoWithDustEnabled(): void
    {
        $data = [
            'temperature' => 35.0,
            'dust_enabled' => true,
            'dust_visibility' => 2000,
        ];

        $weather = new WeatherInfo($data);

        $this->assertTrue($weather->getDustEnabled());
        $this->assertEquals(2000, $weather->getDustVisibility());
    }

    public function testWeatherInfoIsValid(): void
    {
        $weather = new WeatherInfo([
            'temperature' => 20.0,
            'pressure' => 760.0,
        ]);

        $this->assertTrue($weather->valid());
        $this->assertEmpty($weather->listInvalidProperties());
    }

    public function testWeatherInfoJsonSerialize(): void
    {
        $data = [
            'temperature' => 15.0,
            'wind_speed' => 10.0,
            'pressure' => 760.0,
        ];

        $weather = new WeatherInfo($data);
        $json = json_encode($weather);

        $this->assertIsString($json);
        $decoded = json_decode($json, true);
        $this->assertEquals(15.0, $decoded['temperature']);
        $this->assertEquals(10.0, $decoded['wind_speed']);
        $this->assertEquals(760.0, $decoded['pressure']);
    }
}
