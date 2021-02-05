<?php

namespace App\Entity;

use App\Repository\PerunPlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataPlayers")
 * @ORM\Entity(repositoryClass=PerunPlayerRepository::class)
 */
class PerunPlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataPlayers_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="pe_DataPlayers_ucid")
     */
    private $ucid;

    /**
     * @ORM\Column(type="string", length=64, name="pe_DataPlayers_lastname")
     */
    private $lastName;

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
