<?php

namespace App\Perun\Entity;

use App\Perun\Repository\DataPlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataPlayers")
 * * @ORM\Entity(repositoryClass=DataPlayerRepository::class)
 */
class DataPlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataPlayers_id")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64, name="pe_DataPlayers_ucid")
     */
    private ?string $ucid = null;

    /**
     * @ORM\Column(type="string", length=64, name="pe_DataPlayers_lastname")
     */
    private ?string $lastName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUcid(): ?string
    {
        return $this->ucid;
    }

    public function setUcid(string $ucid): self
    {
        $this->ucid = $ucid;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
