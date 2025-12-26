<?php

namespace App\Service;

class SunPositionService
{
    // Latitudes de référence par théâtre (point fixe DCS pour le calcul solaire)
    private const THEATRE_LATITUDES = [
        'caucasus' => 43.6,        // Région de Sochi
        'persiangulf' => 25.0,     // Dubai
        'syria' => 35.0,           // Méditerranée orientale
        'nevada' => 36.0,          // Las Vegas (NTTR)
        'normandy' => 49.0,        // Nord de la France
        'sinai' => 30.0,           // Israël/Sinaï
        'marianaislands' => 15.0,  // Saipan
        'southatlantic' => -52.0,  // Falklands
        'kola' => 69.0,            // Russie du Nord
        'afghanistan' => 34.0,     // Afghanistan central
    ];

    private const DEFAULT_LATITUDE = 45.0;

    // Seuils d'élévation solaire en degrés
    private const ELEVATION_DAY = -6.0;      // Au-dessus = jour (crépuscule civil)
    private const ELEVATION_TWILIGHT = -18.0; // Au-dessus = aube/crépuscule (crépuscule astronomique)

    // Configuration des états
    private const SUN_STATES = [
        'day' => [
            'icon' => 'fas fa-sun',
            'color' => '#ffc107',
            'tooltip' => 'Jour',
        ],
        'night' => [
            'icon' => 'fas fa-moon',
            'color' => '#6c757d',
            'tooltip' => 'Nuit',
        ],
        'dawn' => [
            'icon' => 'fas fa-cloud-sun',
            'color' => '#fd7e14',
            'tooltip' => 'Aube',
        ],
        'dusk' => [
            'icon' => 'fas fa-cloud-sun',
            'color' => '#fd7e14',
            'tooltip' => 'Crépuscule',
        ],
    ];

    public function getSunState(string $dateTime, string $theatre): array
    {
        $dt = $this->parseDateTime($dateTime);
        if (null === $dt) {
            return $this->getDefaultState();
        }

        $latitude = $this->getTheatreLatitude($theatre);
        $elevation = $this->calculateSunElevation($dt, $latitude);
        $state = $this->determineSunState($elevation, $dt);

        return array_merge(
            ['state' => $state],
            self::SUN_STATES[$state]
        );
    }

    public function getTheatreLatitude(string $theatre): float
    {
        $normalizedTheatre = strtolower(str_replace([' ', '_', '-'], '', $theatre));

        return self::THEATRE_LATITUDES[$normalizedTheatre] ?? self::DEFAULT_LATITUDE;
    }

    public function calculateSunElevation(\DateTime $dateTime, float $latitude): float
    {
        // Jour de l'année (1-365)
        $dayOfYear = (int) $dateTime->format('z') + 1;

        // Heure décimale (0-24)
        $hour = (int) $dateTime->format('H');
        $minute = (int) $dateTime->format('i');
        $decimalHour = $hour + $minute / 60.0;

        // Déclinaison solaire (formule simplifiée)
        // d = -23.45 * cos(360/365 * (N + 10))
        $declination = -23.45 * cos(deg2rad(360.0 / 365.0 * ($dayOfYear + 10)));

        // Angle horaire en degrés
        // H = (heure - 12) * 15
        $hourAngle = ($decimalHour - 12.0) * 15.0;

        // Élévation solaire
        // sin(alt) = sin(lat) * sin(d) + cos(lat) * cos(d) * cos(H)
        $latRad = deg2rad($latitude);
        $declRad = deg2rad($declination);
        $hourAngleRad = deg2rad($hourAngle);

        $sinElevation = sin($latRad) * sin($declRad)
            + cos($latRad) * cos($declRad) * cos($hourAngleRad);

        // Clamp pour éviter les erreurs de domaine asin
        $sinElevation = max(-1.0, min(1.0, $sinElevation));

        return rad2deg(asin($sinElevation));
    }

    private function determineSunState(float $elevation, \DateTime $dateTime): string
    {
        $hour = (int) $dateTime->format('H');

        if ($elevation > self::ELEVATION_DAY) {
            return 'day';
        }

        if ($elevation > self::ELEVATION_TWILIGHT) {
            // Aube le matin, crépuscule l'après-midi
            return $hour < 12 ? 'dawn' : 'dusk';
        }

        return 'night';
    }

    private function parseDateTime(string $dateTime): ?\DateTime
    {
        // Format attendu: "YYYY-MM-DD HH:MM:SS"
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        if (false !== $dt) {
            return $dt;
        }

        // Essayer d'autres formats courants
        $dt = \DateTime::createFromFormat('Y-m-d H:i', $dateTime);
        if (false !== $dt) {
            return $dt;
        }

        return null;
    }

    private function getDefaultState(): array
    {
        return array_merge(
            ['state' => 'day'],
            self::SUN_STATES['day']
        );
    }
}
