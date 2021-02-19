<?php


namespace App\Perun\DTO;


class LogStatHourly
{
    private string $hour;
    private \DateTime $start;
    private \DateTime $end;

    private int $nbMembers = 0;
    private int $nbCadets = 0;
    private int $nbGuests = 0;
    private int $nbUnknowns = 0;

    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function setHour(string $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getNbCadets(): int
    {
        return $this->nbCadets;
    }

    public function setNbCadets(int $nbCadets): self
    {
        $this->nbCadets = $nbCadets;

        return $this;
    }

    public function incNbCadets(int $increment = 1): self
    {
        $this->nbCadets += $increment;

        return $this;
    }

    public function getNbGuests(): int
    {
        return $this->nbGuests;
    }

    public function setNbGuests(int $nbGuests): self
    {
        $this->nbGuests = $nbGuests;

        return $this;
    }

    public function incNbGuests(int $increment = 1): self
    {
        $this->nbGuests += $increment;

        return $this;
    }

    public function getNbMembers(): int
    {
        return $this->nbMembers;
    }

    public function setNbMembers(int $nbMembers): self
    {
        $this->nbMembers = $nbMembers;

        return $this;
    }

    public function incNbMembers(int $increment = 1): self
    {
        $this->nbMembers += $increment;

        return $this;
    }

    public function getNbUnknowns(): int
    {
        return $this->nbUnknowns;
    }

    public function setNbUnknowns(int $nbUnknowns): self
    {
        $this->nbUnknowns = $nbUnknowns;

        return $this;
    }

    public function incNbUnknowns(int $increment = 1): self
    {
        $this->nbUnknowns += $increment;

        return $this;
    }
}