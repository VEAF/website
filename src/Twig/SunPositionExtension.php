<?php

namespace App\Twig;

use App\Service\SunPositionService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SunPositionExtension extends AbstractExtension
{
    private SunPositionService $sunPositionService;

    public function __construct(SunPositionService $sunPositionService)
    {
        $this->sunPositionService = $sunPositionService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('mission_time', [$this, 'formatMissionTime']),
            new TwigFilter('mission_date', [$this, 'formatMissionDate']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sun_state', [$this, 'getSunState']),
        ];
    }

    /**
     * Formate la date/heure de mission en heure seule (ex: "14:30")
     */
    public function formatMissionTime(?string $dateTime): string
    {
        if ($dateTime === null || $dateTime === '') {
            return '-';
        }

        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        if ($dt === false) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i', $dateTime);
        }

        if ($dt === false) {
            return '-';
        }

        return $dt->format('H:i');
    }

    /**
     * Formate la date/heure de mission en date complète (ex: "7 août 2025")
     */
    public function formatMissionDate(?string $dateTime): string
    {
        if ($dateTime === null || $dateTime === '') {
            return '-';
        }

        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        if ($dt === false) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i', $dateTime);
        }

        if ($dt === false) {
            return '-';
        }

        $months = [
            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'août',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre',
        ];

        $day = (int) $dt->format('j');
        $month = $months[(int) $dt->format('n')];
        $year = $dt->format('Y');

        return sprintf('%d %s %s', $day, $month, $year);
    }

    /**
     * Retourne l'état du soleil pour une date/heure et un théâtre donnés
     *
     * @return array{state: string, icon: string, color: string, tooltip: string}
     */
    public function getSunState(?string $dateTime, ?string $theatre): array
    {
        if ($dateTime === null || $dateTime === '' || $theatre === null || $theatre === '') {
            return [
                'state' => 'day',
                'icon' => 'fas fa-sun',
                'color' => '#ffc107',
                'tooltip' => 'Jour',
            ];
        }

        return $this->sunPositionService->getSunState($dateTime, $theatre);
    }
}
