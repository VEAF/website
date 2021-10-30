<?php

namespace App\Perun\DTO;

class Coalition
{
    const COALITION_RED = 'red';
    const COALITION_BLUE = 'blue';
    const COALITION_NEUTRAL = 'neutrals';

    private string $name;
    private Position $bullseye;

    public static function createFromJsonArray(array $row): Coalition
    {
        $row += [
            'name' => 'unknown',
            'bullseye' => [],
        ];

        $coalition = new self();
        $coalition->name = $row['name'];

        $coalition->bullseye = Position::createFromJsonArray($row['bullseye']);

        return $coalition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBullseye(): Position
    {
        return $this->bullseye;
    }
}
