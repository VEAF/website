<?php

namespace App\Perun\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_LogEvent",
 *     indexes={
 *        @ORM\Index(name="pe_LogEvent_datetime", columns={"pe_LogEvent_datetime"}),
 *        @ORM\Index(name="pe_LogEvent_type_2", columns={"pe_LogEvent_type"})
 *     })
 * @ORM\Entity(repositoryClass=LogEventRepository::class)
 */
class LogEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", name="pe_LogEvent_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime", name="pe_LogEvent_datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private ?\DateTime $datetime;

    /**
     * @ORM\ManyToOne(targetEntity=DataMissionHash::class)
     * @ORM\JoinColumn(nullable=false, name="pe_LogEvent_missionhash_id", referencedColumnName="pe_DataMissionHashes_id", nullable=true)
     */
    private ?DataMissionHash $mission;

    /**
     * @ORM\Column(type="string", length=100, name="pe_LogEvent_type")
     */
    private ?string $type;

    /**
     * @ORM\Column(type="text", length=65536, name="pe_LogEvent_content")
     */
    private ?string $content;

    /**
     * @ORM\Column(type="string", length=150, name="pe_LogEvent_arg1", nullable=true)
     */
    private ?string $arg1;

    /**
     * @ORM\Column(type="string", length=150, name="pe_LogEvent_arg2", nullable=true)
     */
    private ?string $arg2;

    /**
     * @ORM\Column(type="string", length=150, name="pe_LogEvent_argPlayers", nullable=true)
     */
    private ?string $players;

    public function getId(): ?int
    {
        return $this->id;
    }
}
