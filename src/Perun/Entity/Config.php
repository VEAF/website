<?php

namespace App\Perun\Entity;

use App\Perun\Repository\ConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pe_Config")
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="pe_Config_id")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, name="pe_Config_payload", nullable=true)
     */
    private ?string $payload;

    public function getId(): ?int
    {
        return $this->id;
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
}
