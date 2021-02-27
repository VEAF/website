<?php

namespace App\Perun\DTO;

class PayloadSlot
{
    private string $type;
    private int $multicrewPlace;
    private string $role;
    private string $unitId;

    public static function createFromJsonArray(array $row): PayloadSlot
    {
        $row += [
            'type' => 'unknown',
            'multicrew_place' => 1,
            'role' => 'Pilot',
            'unitId' => '',
        ];

        $slot = new self();
        $slot->type = $row['type'];
        $slot->multicrewPlace = $row['multicrew_place'];
        $slot->role = $row['role'];
        $slot->unitId = $row['unitId'];

        return $slot;
    }

    public function getMulticrewPlace(): int
    {
        return $this->multicrewPlace;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUnitId(): string
    {
        return $this->unitId;
    }
}
