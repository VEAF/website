<?php

namespace App\Perun\DTO;

class GeoPosition
{
    private ?float $lat = null;
    private ?float $lng = null;
    private ?float $alt = null;

    public static function fromRow(array $row): self
    {
        $row += [
            'lat' => null,
            'lng' => null,
            'alt' => null,
        ];

        $p = new GeoPosition();
        $p->setAlt($row['alt']);
        $p->setLat($row['lat']);
        $p->setLng($row['lng']);

        return $p;
    }

    public function getAlt(): ?float
    {
        return $this->alt;
    }

    public function setAlt(?float $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}