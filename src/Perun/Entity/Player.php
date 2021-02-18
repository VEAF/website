<?php

namespace App\Perun\Entity;

use App\Entity\User;
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
     * @ORM\Column(type="bigint", name="pe_DataPlayers_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=150, name="pe_DataPlayers_ucid")
     */
    private ?string $ucid;

    /**
     * @ORM\Column(type="string", length=150, name="pe_DataPlayers_lastname", nullable=true)
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=100, name="pe_DataPlayers_lastip", nullable=true)
     */
    private ?string $lastIp;

    /**
     * @ORM\Column(type="datetime", name="pe_DataPlayers_updated", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $updated;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist"}, fetch="EAGER", mappedBy="perunPlayer")
     */
    private ?User $user;

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

    public function setLastIp(string $lastIp): self
    {
        $this->lastIp = $lastIp;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
