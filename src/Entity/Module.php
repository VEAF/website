<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="code_idx", columns={"code"}),
 *     @ORM\UniqueConstraint(name="name_idx", columns={"name"})
 * })
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 * @UniqueEntity("code")
 * @UniqueEntity("name")
 */
class Module
{
    const TYPE_NONE = 0;
    const TYPE_MAP = 1;
    const TYPE_AIRCRAFT = 2;
    const TYPE_HELICOPTER = 3;
    const TYPE_SPECIAL = 4;

    const TYPES = [
        self::TYPE_MAP => 'Carte',
        self::TYPE_AIRCRAFT => 'Avion',
        self::TYPE_HELICOPTER => 'Hélicoptère',
        self::TYPE_SPECIAL => 'Spécial',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=3)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\Length(min=3)
     */
    private ?string $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (!isset(self::TYPES[$type])) {
            throw new \InvalidArgumentException(sprintf('type %d not supported', $type));
        }
        $this->type = $type;

        return $this;
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

    public function getTypeAsString(): string
    {
        $statuses = self::TYPES;
        if (isset($statuses[$this->type])) {
            return $statuses[$this->type];
        }

        return 'inconnu';
    }
}
