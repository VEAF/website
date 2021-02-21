<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="ucid_idx", columns={"ucid"}),
 * })
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 * @UniqueEntity("ucid")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private ?string $ucid;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $nickname;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $joinAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $lastJoinAt = null;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist"}, fetch="EAGER", mappedBy="player")
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getJoinAt(): ?\DateTime
    {
        return $this->joinAt;
    }

    public function setJoinAt(\DateTime $joinAt): self
    {
        $this->joinAt = $joinAt;

        return $this;
    }

    public function getLastJoinAt(): ?\DateTime
    {
        return $this->lastJoinAt;
    }

    public function setLastJoinAt(\DateTime $lastJoinAt): self
    {
        $this->lastJoinAt = $lastJoinAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
