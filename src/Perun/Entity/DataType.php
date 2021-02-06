<?php

namespace App\Perun\Entity;

use App\Perun\Repository\DataTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataTypes")
 * @ORM\Entity(repositoryClass=DataTypeRepository::class)
 */
class DataType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataTypes_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=100, name="pe_DataTypes_name", nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="datetime", name="pe_DataTypes_update")
     */
    private ?\DateTime $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
