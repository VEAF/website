<?php

namespace App\DTO;

class TimeInterval
{
    private ?\DateTime $start = null;
    private ?\DateTime $end = null;

    public function __construct(string $startString = null, string $endString = null)
    {
        if (null !== $startString) {
            $this->start = new \DateTime($startString);
        }

        if (null !== $endString) {
            $this->end = new \DateTime($endString);
        }
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setEnd(?\DateTime $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function setStart(?\DateTime $start): self
    {
        $this->start = $start;

        return $this;
    }
}
