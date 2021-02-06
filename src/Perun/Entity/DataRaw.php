<?php

namespace App\Perun\Entity;

use App\Perun\Repository\DataRawRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataRaw")
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class DataRaw
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_dataraw_type")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Instance::class)
     * @ORM\JoinColumn(nullable=false, name="pe_DataMissionHashes_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $instance;

    /**
     * @ORM\Column(type="text", length=65536, name="pe_dataraw_payload", nullable=true)
     */
    private ?string $payload;

    /**
     * @ORM\Column(type="datetime", name="pe_dataraw_updated")
     */
    private ?\DateTime $updated;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function setPayload(?string $payload): self
    {
        $this->payload = $payload;

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
