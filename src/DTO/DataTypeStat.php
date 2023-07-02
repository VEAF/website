<?php

namespace App\DTO;

use App\Perun\Entity\DataType;

class DataTypeStat extends AbstractPerunStat
{
    private ?DataType $dataType = null;

    public function getDataType(): ?DataType
    {
        return $this->dataType;
    }

    public function setDataType(?DataType $dataType): self
    {
        $this->dataType = $dataType;

        return $this;
    }
}
