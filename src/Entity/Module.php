<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
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

    const TYPES_WITH_LEVEL = [
        self::TYPE_AIRCRAFT,
        self::TYPE_HELICOPTER,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"id", "module"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"module"})
     */
    private int $type;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=3)
     * @Groups({"module"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\Length(min=3)
     * @Groups({"module"})
     */
    private ?string $code;

    /**
     * @ORM\OneToMany(targetEntity=UserModule::class, mappedBy="module", orphanRemoval=true)
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection|UserModule[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserModule $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setModule($this);
        }

        return $this;
    }

    public function removeUser(UserModule $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getModule() === $this) {
                $user->setModule(null);
            }
        }

        return $this;
    }

    public function isWithLevel(): bool
    {
        return in_array($this->type, self::TYPES_WITH_LEVEL);
    }
}