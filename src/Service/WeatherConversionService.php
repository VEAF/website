<?php

namespace App\Service;

/**
 * Service de conversion des unités météorologiques.
 */
class WeatherConversionService
{
    /**
     * Convertit une température de Celsius vers Fahrenheit.
     */
    public function celsiusToFahrenheit(float $celsius): float
    {
        return ($celsius * 9 / 5) + 32;
    }

    /**
     * Convertit une pression de mmHg vers hPa.
     */
    public function mmHgToHpa(float $mmHg): float
    {
        return $mmHg * 1013.25 / 760;
    }

    /**
     * Convertit une pression de mmHg vers inHg.
     */
    public function mmHgToInHg(float $mmHg): float
    {
        return $mmHg / 25.4;
    }

    /**
     * Convertit une vitesse de m/s vers noeuds (kts).
     */
    public function msToKnots(float $ms): float
    {
        return $ms * 1.94384;
    }

    /**
     * Convertit une vitesse de m/s vers km/h.
     */
    public function msToKmh(float $ms): float
    {
        return $ms * 3.6;
    }

    /**
     * Convertit une direction de vent "d'où vient" vers "vers où va".
     * En météo, la direction du vent indique d'où il vient.
     * Cette méthode retourne la direction opposée (vers où il va).
     */
    public function windDirectionToHeading(int $fromDirection): int
    {
        return ($fromDirection + 180) % 360;
    }

    /**
     * Convertit des mètres en kilomètres.
     */
    public function metersToKm(int $meters): float
    {
        return $meters / 1000;
    }
}
