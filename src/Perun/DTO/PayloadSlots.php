<?php

namespace App\Perun\DTO;

class PayloadSlots
{
    const COALITIONS_ID_MAPPING = [
        1 => 'red',
        2 => 'blue',
    ];

    private array $slots = ['red' => [], 'blue' => []];

    public function addSlot(string $coalition, PayloadSlot $slot)
    {
        if (!isset($this->slots[$coalition])) {
            throw new \InvalidArgumentException(sprintf('coalition %s is undefined', $coalition));
        }

        if (isset($this->slots[$coalition][$slot->getUnitId()])) {
            throw new \InvalidArgumentException(sprintf('unit id %d is already defined for coalition %s', $slot->getUnitId(), $coalition));
        }

        $this->slots[$coalition][$slot->getUnitId()] = $slot;
    }

    public static function createFromJsonArray(array $row): PayloadSlots
    {
        $row += [
            'slots' => [],
            'coalitions' => [],
        ];

        $payloadSlots = new self();
        self::createSlotsFromJsonArray($payloadSlots, $row['slots']);

        return $payloadSlots;
    }

    protected static function createSlotsFromJsonArray(PayloadSlots $payloadSlots, array $row)
    {
        foreach ($payloadSlots->slots as $coalition => $notused) {
            if (isset($row[$coalition]) && is_array($row[$coalition])) {
                static::createSlotsCoalitionFromJsonArray($payloadSlots, $coalition, $row[$coalition]);
            }
        }
    }

    protected static function createSlotsCoalitionFromJsonArray(PayloadSlots $payloadSlots, string $coalition, array $rows)
    {
        foreach ($rows as $row) {
            $payloadSlots->addSlot($coalition, PayloadSlot::createFromJsonArray($row));
        }
    }

    public static function getCoalitionById(int $coalitionId)
    {
        if (!isset(self::COALITIONS_ID_MAPPING[$coalitionId])) {
            return null;
        }

        return self::COALITIONS_ID_MAPPING[$coalitionId];
    }

    public function getSlotByCoalitionIdAndUnitId(int $coalitionId, string $unitId): ?PayloadSlot
    {
        $coalition = static::getCoalitionById($coalitionId);
        if (null !== $coalition) {
            if (isset($this->slots[$coalition][$unitId])) {
                return $this->slots[$coalition][$unitId];
            }
        }

        return null;
    }
}
