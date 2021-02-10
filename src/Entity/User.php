<?php

namespace App\Entity;

use App\Perun\Entity\Player;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @UniqueEntity("nickname")
 */
class User implements UserInterface
{
    const STATUS_GUEST = 0;
    const STATUS_CADET = 1;
    const STATUS_MEMBER = 2;
    const STATUS_SECRETARY_DEPUTY = 3;
    const STATUS_SECRETARY = 4;
    const STATUS_TREASURER_DEPUTY = 5;
    const STATUS_TREASURER = 6;
    const STATUS_PRESIDENT_DEPUTY = 7;
    const STATUS_PRESIDENT = 8;

    const STATUSES = [
        self::STATUS_GUEST => 'invité',
        self::STATUS_CADET => 'cadet',
        self::STATUS_MEMBER => 'membre',
        self::STATUS_SECRETARY_DEPUTY => 'secrétaire adjoint',
        self::STATUS_SECRETARY => 'secrétaire',
        self::STATUS_TREASURER_DEPUTY => 'trésorier adjoint',
        self::STATUS_TREASURER => 'trésorier',
        self::STATUS_PRESIDENT_DEPUTY => 'président adjoint',
        self::STATUS_PRESIDENT => 'président',
    ];

    const STATUSES_MEMBER = [
        self::STATUS_MEMBER,
        self::STATUS_SECRETARY_DEPUTY,
        self::STATUS_SECRETARY,
        self::STATUS_TREASURER_DEPUTY,
        self::STATUS_TREASURER,
        self::STATUS_PRESIDENT_DEPUTY,
        self::STATUS_PRESIDENT,
    ];

    const STATUSES_OFFICE = [
        self::STATUS_SECRETARY_DEPUTY,
        self::STATUS_SECRETARY,
        self::STATUS_TREASURER_DEPUTY,
        self::STATUS_TREASURER,
        self::STATUS_PRESIDENT_DEPUTY,
        self::STATUS_PRESIDENT,
    ];

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    const ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(min=3)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="simple_array")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @var string|null
     */
    protected ?string $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $passwordRequestToken;

    /**
     * @ORM\OneToOne(targetEntity=Player::class, cascade={"persist"}, fetch="EAGER", inversedBy="user")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="pe_DataPlayers_id", nullable=true)
     */
    private ?Player $player;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3)
     */
    private ?string $nickname;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private ?\DateTime $updatedAt = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $simBms = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $simDcs = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $status = self::STATUS_GUEST;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function getPasswordRequestToken(): ?string
    {
        return $this->passwordRequestToken;
    }

    public function setPasswordRequestToken(?string $passwordRequestToken): User
    {
        $this->passwordRequestToken = $passwordRequestToken;

        return $this;
    }

    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSimBms(): ?bool
    {
        return $this->simBms;
    }

    public function setSimBms(bool $simBms): self
    {
        $this->simBms = $simBms;

        return $this;
    }

    public function getSimDcs(): ?bool
    {
        return $this->simDcs;
    }

    public function setSimDcs(bool $simDcs): self
    {
        $this->simDcs = $simDcs;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getStatusAsString(): string
    {
        $statuses = self::STATUSES;
        if (isset($statuses[$this->status])) {
            return $statuses[$this->status];
        }
        return 'inconnu';
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isMember(): ?bool
    {
        return in_array($this->status, self::STATUSES_MEMBER);
    }

    public function isOffice(): ?bool
    {
        return in_array($this->status, self::STATUSES_OFFICE);
    }
}
