<?php

namespace App\Perun\DTO;

class Position
{
    private float $x;
    private float $y;

    public function __construct(float $x = 0.0, float $y = 0.0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function createFromJsonArray(array $row): Position
    {
        $row += [
            'x' => 0.0,
            'y' => 0.0,
        ];

        $position = new self();
        $position->x = $row['x'];
        $position->y = $row['y'];

        return $position;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}
