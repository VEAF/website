<?php

namespace App\Twig;

use App\Component\DegMinSec;
use App\Perun\DTO\Position;
use App\Service\ProjectionService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class ProjectionExtension extends AbstractExtension
{

    private ProjectionService $projection;

    public function __construct(ProjectionService $projection)
    {
        $this->projection = $projection;
    }

    /**
     * @return array|\Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return array(
            new TwigFilter('xyToLat', array($this, 'xyToLat')),
            new TwigFilter('xyToLong', array($this, 'xyToLong')),
            new TwigFilter('xyToLL', array($this, 'xyToLL')),
            new TwigFilter('latDec', array($this, 'latDec')),
            new TwigFilter('latLongDec', array($this, 'latLongDec')),
            new TwigFilter('longDec', array($this, 'longDec')),
            new TwigFilter('positionArray', array($this, 'positionArray')),
        );
    }

    public function xyToLL(Position $p, string $theatre): Position
    {
        return $this->projection->xyToLL($p, $theatre);
    }

    public function xyToLat(Position $p, string $theatre): float
    {
        return ($this->projection->xyToLL($p, $theatre))->getY();
    }

    public function xyToLong(Position $p, string $theatre): float
    {
        return ($this->projection->xyToLL($p, $theatre))->getX();
    }

    public function latDec(Position $p): string
    {
        return (new DegMinSec($p->getY()))->format('lat');
    }

    /**
     * @param Position $p
     *
     * @return string
     */
    public function latLongDec(Position $p)
    {
        return (new DegMinSec($p->getY()))->format('lat') . " " . (new DegMinSec($p->getX()))->format('long');
    }

    /**
     * @param Position $p
     *
     * @return string
     */
    public function longDec(Position $p)
    {
        return (new DegMinSec($p->getX()))->format('long');
    }

    /**
     * @param Position $p
     *
     * @return array
     */
    public function positionArray(Position $p): array
    {
        return [$p->getX(), $p->getY()];
    }
}