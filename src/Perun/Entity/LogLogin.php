<?php

namespace App\Perun\Entity;

use App\Perun\Repository\LogLoginRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_LogLogins")
 * @ORM\Entity(repositoryClass=LogLoginRepository::class)
 */
class LogLogin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", name="pe_LogLogins_id")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Instance::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogLogins_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $instance;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogLogins_playerid", referencedColumnName="pe_DataPlayers_id", nullable=true)
     */
    private ?Player $player;

    /**
     * @ORM\Column(type="datetime", name="pe_LogLogins_datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $datetime;

    /**
     * @ORM\Column(type="string", length=150, name="pe_LogLogins_name")
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=100, name="pe_LogLogins_ip")
     */
    private ?string $ip;

    public function getId(): ?int
    {
        return $this->id;
    }
}
