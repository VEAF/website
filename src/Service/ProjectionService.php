<?php

namespace App\Service;

use App\Perun\DTO\Position;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;

class ProjectionService
{
    private Proj4php $proj4;
    private Proj $projSource;

    private $projections = [];

    public function __construct()
    {
        $this->proj4 = new Proj4php();
        $this->projSource = new Proj('EPSG:4326', $this->proj4);
    }

    private function getProjection(string $theatre): Proj
    {
        $theatre = strtolower($theatre);
        if (!isset($this->projections[$theatre])) {
            switch (strtolower($theatre)) {
                case 'persiangulf':
                    $projection = new Proj('+proj=tmerc +lat_0=26.1718 +lon_0=56.2419 +k_0=0.9996 +x_0=0 +y_0=0', $this->proj4);
                    break;
                case 'caucasus':
                    $projection = new Proj('+proj=tmerc +lat_0=0 +lon_0=33 +k_0=0.9996 +x_0=-99517 +y_0=-4998115', $this->proj4);
                    break;
                case 'syria':
                    // picked from editor:
                    // x=0 y=0, lat=35°1.315' (35.21917) - long=35°54.33' (35.9055) k=random (copied from caucasus, @todo fix k)
                    $projection = new Proj('+proj=tmerc +lat_0=35.21917 +lon_0=35.9055 +k_0=0.9996 +x_0=0 +y_0=0', $this->proj4);
                    break;
                case 'marianaislands':
                    // picked from editor:
                    // x=0 y=0, lat=13°29.1' (13.485) - long=144°47.852' (144.7533) k=random (copied from caucasus, @todo fix k)
                    $projection = new Proj('+proj=tmerc +lat_0=13.485 +lon_0=144.7533 +k_0=0.9996 +x_0=0 +y_0=0', $this->proj4);
                    break;

                default:
                    throw new \InvalidArgumentException(sprintf('unsupported theatre %s, please report to https://github.com/VEAF/website/issues', $theatre));
            }
            $this->projections[$theatre] = $projection;
        }

        return $this->projections[$theatre];
    }

    public function xyToLL(Position $p, string $theatre): Position
    {
        // need to swap X and Y
        $source = new Point($p->getY(), $p->getX(), null, $this->getProjection($theatre));
        $destination = $this->proj4->transform($this->projSource, $source);

        return new Position($destination->x, $destination->y);
    }
}
