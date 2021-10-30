<?php

namespace App\Service;

use App\Perun\DTO\Mission;
use App\Twig\ProjectionExtension;

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
     * get Data ready to encode for front view.
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
