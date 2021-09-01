<?php

namespace App\Service;


use App\Perun\DTO\Mission;
use App\Perun\DTO\Position;
use App\Twig\ProjectionExtension;
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class MapService
{
    private ProjectionService $projectionService;
    private ProjectionExtension $projectionExtension;

    public function __construct(ProjectionService $projectionService, ProjectionExtension $projectionExtension)
    {
        $this->projectionService = $projectionService;
        $this->projectionExtension = $projectionExtension;
    }

    /**
     * get Data ready to encode for front view
     * @return array
     */
    public function getMapData(Mission $mission): array
    {
        $data = [];
        $data['bullseye']['blue'] = $this->projectionExtension->positionArray($this->projectionExtension->xyToLL($mission->getCoalition('blue')->getBullseye(), $mission->getTheatre()));
        $data['bullseye']['red'] = $this->projectionExtension->positionArray($this->projectionExtension->xyToLL($mission->getCoalition('red')->getBullseye(), $mission->getTheatre()));
        $data['mapCenter'] = $data['bullseye']['blue'];

        return $data;
    }

}