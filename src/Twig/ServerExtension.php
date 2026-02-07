<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ServerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('strip_icao', [$this, 'stripIcao']),
        ];
    }

    public function stripIcao(string $name): string
    {
        return preg_replace('/ ICAO\s+\S+$/', '', $name);
    }
}
