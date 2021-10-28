<?php

namespace App\Component;

class DegMinSec
{
    private int $sign;
    private int $deg;
    private int $min;
    private float $sec;

    public function __construct(float $decimal)
    {
        $this->sign = ($decimal >= 0 ? 1 : -1);
        $this->deg = (int) floor(abs($decimal));
        $rest = abs($decimal) - $this->deg;
        $seconds = 3600 * $rest;
        $this->min = (int) floor($seconds / 60);
        $this->sec = $seconds - 60 * $this->min;
    }

    public function getSign(): int
    {
        return $this->sign;
    }

    public function getDeg(): int
    {
        return $this->deg;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getSec(): float
    {
        return $this->sec;
    }

    /**
     * @param string $type 'lat'|long'
     */
    public function format($type): string
    {
        if ('lat' == $type) {
            return sprintf("%s%02d°%02d'%02d", $this->sign > 0 ? 'N' : 'S', $this->deg, $this->min, $this->sec);
        } elseif ('long' == $type) {
            return sprintf("%s%03d°%02d'%02d", $this->sign > 0 ? 'E' : 'W', $this->deg, $this->min, $this->sec);
        } else {
            throw new \InvalidArgumentException('unknown type '.$type);
        }
    }
}
