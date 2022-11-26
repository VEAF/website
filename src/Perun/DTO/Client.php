<?php

namespace App\Perun\DTO;

/**
 * pe_dataraw_type: 100
 */
class Client
{
    private ?string $guid = null;
    private ?string $name = null;
    private ?int $seat = null;
    private ?int $coalitionId = null;
    private ?bool $allowRecord = null;

    private ?GeoPosition $position = null;

    /**
     * @return Client[]|array
     */
    public static function fromJson(string $json): array
    {
        $data = json_decode($json, true);

        $data += ['Clients' => []];

        return static::fromRows($data['Clients']);
    }

    /**
     * @return Client[]|array
     */
    public static function fromRows(array $rows): array
    {
        $clients = [];

        foreach ($rows as $row) {
            $clients[] = static::fromRow($row);
        }

        return $clients;
    }

    public static function fromRow(array $row): self
    {
        $row += [
            'ClientGuid' => null,
            'Name' => null,
            'Seat' => null,
            'Coalition' => null,
            'AllowRecord' => null,
            'LatLngPosition' => [],
        ];

        $client = new Client();

        $client->setAllowRecord($row['AllowRecord']);
        $client->setGuid($row['ClientGuid']);
        $client->setName($row['Name']);
        $client->setCoalitionId($row['Coalition']);
        $client->setPosition(GeoPosition::fromRow($row['LatLngPosition']));

        return $client;
    }

    public function getAllowRecord(): ?bool
    {
        return $this->allowRecord;
    }

    public function setAllowRecord(?bool $allowRecord): self
    {
        $this->allowRecord = $allowRecord;

        return $this;
    }

    public function getCoalitionId(): ?int
    {
        return $this->coalitionId;
    }

    public function setCoalitionId(?int $coalitionId): self
    {
        $this->coalitionId = $coalitionId;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?GeoPosition
    {
        return $this->position;
    }

    public function setPosition(?GeoPosition $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(?int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }
}