<?php

namespace App\Twig;

use App\Component\DegMinSec;
use App\Perun\DTO\Position;
use App\Service\ProjectionService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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
        return [
            new TwigFilter('xyToLat', [$this, 'xyToLat']),
            new TwigFilter('xyToLong', [$this, 'xyToLong']),
            new TwigFilter('xyToLL', [$this, 'xyToLL']),
            new TwigFilter('latDec', [$this, 'latDec']),
            new TwigFilter('latLongDec', [$this, 'latLongDec']),
            new TwigFilter('longDec', [$this, 'longDec']),
            new TwigFilter('positionArray', [$this, 'positionArray']),
        ];
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
     * @return string
     */
    public function latLongDec(Position $p)
    {
        return (new DegMinSec($p->getY()))->format('lat').' '.(new DegMinSec($p->getX()))->format('long');
    }

    /**
     * @return string
     */
    public function longDec(Position $p)
    {
        return (new DegMinSec($p->getX()))->format('long');
    }

    public function positionArray(Position $p): array
    {
        return [$p->getX(), $p->getY()];
    }
}
