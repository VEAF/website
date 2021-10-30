<?php

namespace App\Perun\DTO;

class Weather
{
    const QNH_UNIT_MMHG = 'mmHg';
    const QNH_UNIT_HPA = 'hPa';
    const QNH_UNIT_INHG = 'inHg';
    const TEMPERATURE_UNIT_CELSIUS = 'celsius';
    const TEMPERATURE_UNIT_FAHRENHEIT = 'fahrenheit';

    private bool $fogEnabled;
    private int $fogThickness;
    private int $fogVisibility;
    private float $atmosphereType;
    private float $groundTurbulence;
    private float $temperature;
    private int $typeWeather;
    private float $qnh;

    /* @var Wind[] associative windType:Wind */
    private array $winds;

    private int $dustDensity;
    private int $visibilityDistance;
    private int $cloudsDensity;
    private int $cloudsThickness;
    private string $cloudsPreset;
    private int $cloudsBase;
    private int $cloudsPrecipitations;

    public static function createFromJsonArray(array $row): Weather
    {
        $row += [
            'atmosphere_type' => 0,
            'groundTurbulence' => 0,
            'enable_fog' => false,
            'season' => [],
            //'temperature' => 15.0,
            'type_weather' => 2,
            'qnh' => 760.0,
            'cyclones' => [],
            'wind' => [],
            //"at8000" => array:2 [ …2]
            //"atGround" => array:2 [ …2]
            //"at2000" => array:2 [ …2]
            'dust_density' => 0,
            'visibility' => [],
            // "distance" => 80000
            'fog' => [],
            //"thickness" => 0
            //"visibility" => 0
            'enable_dust' => false,
            'clouds' => [],
            //"density" => 0
            //"thickness" => 200
            //"preset" => "Preset3"
            //"base" => 5000
            //"iprecptns" => 0
        ];

        $weather = new self();

        $weather->qnh = $row['qnh'];
        $weather->fogEnabled = $row['enable_fog'];
        $weather->dustDensity = $row['dust_density'];
        $weather->parseSeason($row['season']);
        $weather->parseVisibility($row['visibility']);
        $weather->winds = [];

        foreach (['atGround' => 0, 'at2000' => 2000, 'at8000' => 8000] as $windType => $windLevel) {
            if (isset($row['wind'][$windType])) {
                $weather->winds[$windLevel] = Wind::createFromJsonArray($row['wind'][$windType]);
            }
        }
        $weather->parseClouds($row['clouds']);

        return $weather;
    }

    private function parseSeason(array $row): void
    {
        $row += [
            'temperature' => 15.0,
        ];

        $this->temperature = $row['temperature'];
    }

    private function parseVisibility(array $row): void
    {
        $row += [
            'distance' => 0,
        ];

        $this->visibilityDistance = $row['distance'];
    }

    private function parseClouds(array $row): void
    {
        $row += [
            'density' => 0,
            'thickness' => 0,
            'preset' => 'unknown',
            'base' => 0,
            'iprecptns' => 0,
        ];

        $this->cloudsDensity = $row['density'];
        $this->cloudsThickness = $row['thickness'];
        $this->cloudsPreset = $row['preset'];
        $this->cloudsBase = $row['base'];
        $this->cloudsPrecipitations = $row['iprecptns'];
    }

    public function getTemperature(string $unit): float
    {
        switch ($unit) {
            case static::TEMPERATURE_UNIT_FAHRENHEIT:
                return 9 / 5 * $this->temperature + 32;
            case static::TEMPERATURE_UNIT_CELSIUS:
            default:
                return $this->temperature;
        }
    }

    public function getQnh(string $unit): float
    {
        switch ($unit) {
            case static::QNH_UNIT_INHG:
                return $this->qnh / 25.4;
            case static::QNH_UNIT_HPA:
                return $this->qnh * 1013.25 / 760;
            case static::QNH_UNIT_MMHG:
            default:
                return $this->qnh;
        }
    }

    public function getWinds(): array
    {
        return $this->winds;
    }

    public function getCloudsDensity(): int
    {
        return $this->cloudsDensity;
    }

    public function getCloudsThickness(): int
    {
        return $this->cloudsThickness;
    }

    public function getCloudsPreset(): string
    {
        return $this->cloudsPreset;
    }

    public function getCloudsBase(): int
    {
        return $this->cloudsBase;
    }

    public function getCloudsPrecipitations(): int
    {
        return $this->cloudsPrecipitations;
    }

    public function getVisibilityDistance(): int
    {
        return $this->visibilityDistance;
    }

    public function getFogEnabled(): bool
    {
        return $this->fogEnabled;
    }

    public function getDustDensity(): int
    {
        return $this->dustDensity;
    }
}
