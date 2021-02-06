<?php

namespace App\Perun\Entity;

use App\Perun\Repository\InstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_DataMissionHashes")
 * @ORM\Entity(repositoryClass=DataMissionHashRepository::class)
 */
class DataMissionHash
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pe_DataMissionHashes_id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, name="pe_DataMissionHashes_hash", nullable=true)
     */
    private ?string $hash;

    /**
     * @ORM\ManyToOne(targetEntity=Instance::class)
     * @ORM\JoinColumn(nullable=false, name="pe_DataMissionHashes_instance", referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $instance;

    /**
     * @ORM\Column(type="datetime", name="pe_DataMissionHashes_datetime")
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
