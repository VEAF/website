<?php

namespace App\Perun\DTO;

use App\Perun\Entity\Instance;

class Mission
{
    private string $theatre;
    private \DateTime $startDate;
    private Weather $weather;
    private ?Instance $instance;
    private array $coalitions = [];

    public static function createFromJsonArray(array $row): Mission
    {
        $row += [
            'theatre' => 'unknown',
            'start_time' => 0,
            'date' => [],
            'weather' => [],
            'coalition' => [],
        ];

        $mission = new self();
        $mission->theatre = $row['theatre'];

        $startTime = $row['start_time'];
        $mission->startDate = static::parseDateFromJsonArray($row['date']);
        $mission->startDate->modify(sprintf('+%d seconds', $startTime));
        $mission->weather = Weather::createFromJsonArray($row['weather']);
        $mission->coalitions = static::parseCoalitionsFromJsonArray($row['coalition']);

        return $mission;
    }

    private static function parseDateFromJsonArray(array $row): \DateTime
    {
        $row += [
            'Year' => 2011,
            'Month' => 6,
            'Day' => 23,
        ];

        $date = new \DateTime(sprintf('%04d-%02d-%02d', $row['Year'], $row['Month'], $row['Day']));

        return $date;
    }

    /**
     * @return array|Coalition[]
     */
    private static function parseCoalitionsFromJsonArray(array $row): array
    {
        $coalitions = [];

        foreach ($row as $coaltionCode => $coaltionRow) {
            $coalitions[$coaltionCode] = Coalition::createFromJsonArray($coaltionRow);
        }

        return $coalitions;
    }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function hasTheatre(): bool
    {
        return 'unknown' !== $this->theatre;
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getTheatre(): string
    {
        return $this->theatre;
    }

    public function getWeather(): Weather
    {
        return $this->weather;
    }

    public function getMissionCurrentDateTime(): \DateTime
    {
        $date = clone $this->startDate;

        if (null !== $this->instance) {
            $date->modify(sprintf('+%d seconds', $this->instance->getModelTime()));
        }

        return $date;
    }

    /**
     * @return array|Coalition[]
     */
    public function getCoalitions(): array
    {
        return $this->coalitions;
    }

    public function getCoalition(string $coalitionCode): Coalition
    {
        return $this->coalitions[$coalitionCode];
    }
}
