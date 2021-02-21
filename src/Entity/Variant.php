<?php

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="code_idx", columns={"code"}),
 *     @ORM\UniqueConstraint(name="name_idx", columns={"name"})
 * })
 * @ORM\Entity(repositoryClass=VariantRepository::class)
 * @UniqueEntity("code")
 * @UniqueEntity("name")
 */
class Variant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * Code used by SLMOD.
     *
     * @ORM\Column(type="string", length=32)
     */
    private ?string $code;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\NotBlank
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="variants")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Module $module = null;

    /**
     * @ORM\OneToMany(targetEntity=VariantStat::class, mappedBy="variant")
     */
    private $stats;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
