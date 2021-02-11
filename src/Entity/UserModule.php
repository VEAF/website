<?php

namespace App\Entity;

use App\Repository\UserModuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="usermodule_idx", columns={"user_id","module_id"})
 * })
 * @ORM\Entity(repositoryClass=UserModuleRepository::class)
 * @UniqueEntity(fields = {"user", "module"})
 */
class UserModule
{
    const LEVEL_UNKNOWN = 0;
    const LEVEL_CADET = 1;
    const LEVEL_MISSION = 2;
    const LEVEL_INSTRUCTOR = 3;

    const LEVELS = [
        self::LEVEL_UNKNOWN => 'inconnu',
        self::LEVEL_CADET => 'cadet',
        self::LEVEL_MISSION => 'mission',
        self::LEVEL_INSTRUCTOR => 'instructeur',
    ];

    const LEVEL_KEYS = [
        self::LEVEL_UNKNOWN,
        self::LEVEL_CADET,
        self::LEVEL_MISSION,
        self::LEVEL_INSTRUCTOR,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_module"})
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="modules", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_module", "module", "id"})
     */
    private ?Module $module;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_module"})
     */
    private bool $active = false;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user_module"})
     */
    private int $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevelAsString(): string
    {
        if (isset(self::LEVELS[$this->level])) {
            return self::LEVELS[$this->level];
        }

        return 'inconnu';
    }

    public function getLevelAsInt(): array
    {
    }
}
