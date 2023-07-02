<?php

namespace App\DTO;

use App\Entity\Player;
use App\Perun\Entity\DataPlayer;

class DataPlayerStat extends AbstractPerunStat
{
    private ?DataPlayer $dataPlayer;
    private ?Player $player;

    public function getDataPlayer(): ?DataPlayer
    {
        return $this->dataPlayer;
    }

    public function setDataPlayer(?DataPlayer $player): self
    {
        $this->dataPlayer = $player;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }
}
