<?php

namespace App\Entity;

use App\Perun\Entity\Instance;
use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 */
class Server
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
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private ?string $code;

    /**
     * @ORM\OneToOne(targetEntity=Instance::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="pe_OnlineStatus_instance")
     */
    private ?Instance $perunInstance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPerunInstance(): ?Instance
    {
        return $this->perunInstance;
    }

    public function setPerunInstance(?Instance $perunInstance): self
    {
        $this->perunInstance = $perunInstance;

        return $this;
    }
}
