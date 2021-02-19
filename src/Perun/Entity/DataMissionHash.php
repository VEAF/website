<?php

namespace App\Perun\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Perun\Repository\DataMissionHashRepository;

/**
 * @ORM\Table(name="pe_DataMissionHashes")
 * @ORM\Entity(repositoryClass=DataMissionHashRepository::class)
 */
class DataMissionHash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", name="pe_DataMissionHashes_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=150, name="pe_DataMissionHashes_hash", nullable=false)
     */
    private ?string $hash;

    /**
     * @ORM\ManyToOne(targetEntity=Instance::class)
     * @ORM\JoinColumn(nullable=false, name="pe_DataMissionHashes_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $instance;

    /**
     * @ORM\Column(type="datetime", name="pe_DataMissionHashes_datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=false)
     */
    private ?\DateTime $datetime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }
}
