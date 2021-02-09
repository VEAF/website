<?php

namespace App\Perun\Entity;

use App\Perun\Repository\LogChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_LogChat")
 * @ORM\Entity(repositoryClass=LogChatRepository::class)
 */
class LogChat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", name="pe_LogChat_id")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=DataMissionHash::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogChat_missionhash_id", referencedColumnName="pe_DataMissionHashes_id", nullable=true)
     */
    private ?DataMissionHash $mission;

    /**
     * I think the original data model is wrong.
     *
     * --ORM\ManyToOne(targetEntity=Player::class)
     * --ORM\JoinColumn(nullable=false, name="pe_LogChat_playerid", referencedColumnName="pe_DataPlayers_id")
     */
    private ?Player $player;

    /**
     * Mitch: I don't why, but it's a varchar(100) column ...
     *
     * @ORM\Column(type="string", length=100, name="pe_LogChat_playerid")
     */
    private ?string $playerId;

    /**
     * @ORM\Column(type="datetime", name="pe_LogChat_datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $datetime;

    /**
     * @ORM\Column(type="text", length=65536, name="pe_LogChat_msg")
     */
    private ?string $message;

    /**
     * @ORM\Column(type="string", length=10, name="pe_LogChat_all")
     */
    private ?string $all;

    public function getId(): ?int
    {
        return $this->id;
    }
}
