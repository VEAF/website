<?php

namespace App\Entity;

use App\Entity\Recruitment\Event;
use App\Perun\Entity\Player as PerunPlayer;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="email_idx", columns={"email"}),
 *     @ORM\UniqueConstraint(name="nickname_idx", columns={"nickname"})
 * })
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @UniqueEntity("nickname")
 */
class User implements UserInterface
{
    const STATUS_UNKNOWN = 0;
    const STATUS_CADET = 1;
    const STATUS_MEMBER = 2;
    const STATUS_SECRETARY_DEPUTY = 3;
    const STATUS_SECRETARY = 4;
    const STATUS_TREASURER_DEPUTY = 5;
    const STATUS_TREASURER = 6;
    const STATUS_PRESIDENT_DEPUTY = 7;
    const STATUS_PRESIDENT = 8;
    const STATUS_GUEST = 9;

    const STATUSES = [
        self::STATUS_UNKNOWN => 'inconnu',
        self::STATUS_CADET => 'cadet',
        self::STATUS_MEMBER => 'membre',
        self::STATUS_SECRETARY_DEPUTY => 'secrétaire adjoint',
        self::STATUS_SECRETARY => 'secrétaire',
        self::STATUS_TREASURER_DEPUTY => 'trésorier adjoint',
        self::STATUS_TREASURER => 'trésorier',
        self::STATUS_PRESIDENT_DEPUTY => 'président adjoint',
        self::STATUS_PRESIDENT => 'président',
        self::STATUS_GUEST => 'invité',
    ];

    const STATUSES_ALL = [
        self::STATUS_UNKNOWN,
        self::STATUS_CADET,
        self::STATUS_MEMBER,
        self::STATUS_SECRETARY_DEPUTY,
        self::STATUS_SECRETARY,
        self::STATUS_TREASURER_DEPUTY,
        self::STATUS_TREASURER,
        self::STATUS_PRESIDENT_DEPUTY,
        self::STATUS_PRESIDENT,
        self::STATUS_GUEST,
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

    const GROUP_ALL = 'all';
    const GROUP_CADETS = 'cadets';
    const GROUP_MEMBERS = 'members';
    const GROUP_OFFICE = 'office';

    const GROUPS = [
        self::GROUP_ALL => 'Tout le monde',
        self::GROUP_CADETS => 'Cadets',
        self::GROUP_MEMBERS => 'Membres',
        self::GROUP_OFFICE => 'Bureau',
    ];

    const ROLE_USER = 'user';
    const ROLE_RECRUITER = 'recruteur';
    const ROLE_ADMIN = 'admin';

    const ROLES = [
        'ROLE_USER' => self::ROLE_USER,
        'ROLE_RECRUITER' => self::ROLE_RECRUITER,
        'ROLE_ADMIN' => self::ROLE_ADMIN,
    ];

    const CADET_MIN_FLIGHTS = 5;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(min=3, max=180)
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

    protected ?string $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $passwordRequestToken;

    /**
     * @ORM\OneToOne(targetEntity=PerunPlayer::class, cascade={"persist"}, fetch="EAGER", inversedBy="user")
     * @ORM\JoinColumn(name="perun_player_id", referencedColumnName="pe_DataPlayers_id", nullable=true)
     */
    private ?PerunPlayer $perunPlayer;

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
    private int $status = self::STATUS_UNKNOWN;

    /**
     * @var UserModule[]|ArrayCollection|array
     *
     * @ORM\OneToMany(targetEntity=UserModule::class, mappedBy="user", orphanRemoval=true)
     */
    private $modules;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="owner")
     */
    private $files;

    /**
     * @ORM\OneToOne(targetEntity=Player::class, cascade={"persist", "remove"}, inversedBy="user")
     */
    private $player;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="user")
     */
    private $recruitmentEvents;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $needPresentation = false;

    /**
     * @ORM\Column(type="integer")
     */
    private int $cadetFlights = 0;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(min=3, max=64)
     */
    private ?string $discord = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(min=3, max=64)
     */
    private ?string $forum = null;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->recruitmentEvents = new ArrayCollection();
    }

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
        return (string) $this->email;
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
        return (string) $this->password;
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

    public function getPerunPlayer(): ?PerunPlayer
    {
        return $this->perunPlayer;
    }

    public function setPerunPlayer(?PerunPlayer $perunPlayer): self
    {
        $this->perunPlayer = $perunPlayer;

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

    public static function getStatusByIdAsString(int $id)
    {
        if (isset(self::STATUSES[$id])) {
            return self::STATUSES[$id];
        }

        return 'inconnu';
    }

    public function getStatusAsString(): string
    {
        return self::getStatusByIdAsString($this->status);
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

    public function isCadet(): ?bool
    {
        return self::STATUS_CADET === $this->status;
    }

    public function isOffice(): ?bool
    {
        return in_array($this->status, self::STATUSES_OFFICE);
    }

    /**
     * @return Collection|UserModule[]
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(UserModule $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->setUser($this);
        }

        return $this;
    }

    public function removeModule(UserModule $module): self
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getUser() === $this) {
                $module->setUser(null);
            }
        }

        return $this;
    }

    public function hasModule(Module $module)
    {
        foreach ($this->modules as $userModule) {
            if ($userModule->getModule()->getId() == $module->getId()) {
                return true;
            }
        }

        return false;
    }

    public static function getGroupStatuses(string $group)
    {
        switch ($group) {
            case self::GROUP_ALL:
                return self::STATUSES_ALL;
            case self::GROUP_CADETS:
                return [self::STATUS_CADET];
            case self::GROUP_MEMBERS:
                return self::STATUSES_MEMBER;
            case self::GROUP_OFFICE:
                return self::STATUSES_OFFICE;
            default:
                if (!isset(self::GROUPS[$group])) {
                    throw new \InvalidArgumentException(sprintf('unknown group %s', $group));
                }
        }
    }

    public function __toString(): string
    {
        return $this->nickname;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setOwner($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getOwner() === $this) {
                $file->setOwner(null);
            }
        }

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

    /**
     * @deprecated since 1.5.0+
     */
    public function isProfileFilled(): bool
    {
        return $this->simDcs || $this->simBms;
    }

    /**
     * @return Collection|Event[]
     */
    public function getRecruitmentEvents(): Collection
    {
        return $this->recruitmentEvents;
    }

    public function addRecruitmentEvent(Event $event): self
    {
        if (!$this->recruitmentEvents->contains($event)) {
            $this->recruitmentEvents[] = $event;
            $event->setUser($this);
        }

        return $this;
    }

    public function removeRecruitmentEvent(Event $event): self
    {
        if ($this->recruitmentEvents->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    public function getRecruitmentStatus(): string
    {
        switch ($this->status) {
            case self::STATUS_CADET:
                return 'cadet';
            case self::STATUS_GUEST:
                return 'guest';
            case self::STATUS_MEMBER:
            case self::STATUS_TREASURER:
            case self::STATUS_TREASURER_DEPUTY:
            case self::STATUS_SECRETARY:
            case self::STATUS_SECRETARY_DEPUTY:
            case self::STATUS_PRESIDENT:
            case self::STATUS_PRESIDENT_DEPUTY:
                return 'member';
            case self::STATUS_UNKNOWN:
            default:
                return 'unknown';
        }

        return $this->getStatusAsString();
    }

    public function setRecruitmentStatus(string $status): void
    {
        switch ($status) {
            case 'cadet':
                $this->setStatus(self::STATUS_CADET);
                break;
            case 'member':
                $this->setStatus(self::STATUS_MEMBER);
                break;
            case 'guest':
                $this->setStatus(self::STATUS_GUEST);
                break;
            default:
        }
    }

    public static function getRoleAsString(string $role): string
    {
        if (isset(self::ROLES[$role])) {
            return self::ROLES[$role];
        } else {
            return $role;
        }
    }

    public function getNeedPresentation(): ?bool
    {
        return $this->needPresentation;
    }

    public function setNeedPresentation(bool $needPresentation): self
    {
        $this->needPresentation = $needPresentation;

        return $this;
    }

    public function getCadetFlights(): ?int
    {
        return $this->cadetFlights;
    }

    public function setCadetFlights(int $cadetFlights): self
    {
        $this->cadetFlights = $cadetFlights;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getForum(): ?string
    {
        return $this->forum;
    }

    public function setForum(?string $forum): self
    {
        $this->forum = $forum;

        return $this;
    }
}
