<?php

namespace App\Perun\DTO;

class Wind
{
    const SPEED_UNIT_KNOTS = 'kts';
    const SPEED_UNIT_MS = 'ms';
    const SPEED_UNIT_KPH = 'kph';

    private float $speed;
    private float $direction;

    public static function createFromJsonArray(array $row): Wind
    {
        $row += [
            'speed' => 0,
            'dir' => 0,
        ];

        $wind = new self();

        $wind->speed = $row['speed'];
        $wind->direction = $row['dir'];

        return $wind;
    }

    public function getSpeed(string $unit): float
    {
        switch ($unit) {
            case static::SPEED_UNIT_KNOTS:
                return 1.94384 * $this->speed;
            case static::SPEED_UNIT_KPH:
                return 3.6 * $this->speed;
            case static::SPEED_UNIT_MS:
            default:
                return $this->speed;
        }
    }

    public function getDirection(): float
    {
        return $this->direction;
    }
}
