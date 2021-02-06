<?php

namespace App\Perun\Entity;

use App\Perun\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataPlayers")
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataPlayers_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=150, name="pe_DataPlayers_ucid")
     */
    private ?string $ucid;

    /**
     * @ORM\Column(type="string", length=150, name="pe_DataPlayers_lastname")
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=100, name="pe_DataPlayers_lastip")
     */
    private ?string $lastIp;

    /**
     * @ORM\Column(type="datetime", name="pe_DataPlayers_updated")
     */
    private ?\DateTime $updated;

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

    public function getLastIp(): ?string
    {
        return $this->lastIp;
    }

    public function setLastIp($lastIp): self
    {
        $this->lastIp = $lastIp;
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
