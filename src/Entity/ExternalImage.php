<?php

namespace App\Entity;

use App\Entity\Calendar\Event;
use App\Repository\ExternalImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternalImageRepository::class)
 */
class ExternalImage
{
    const SOURCE_TYPE_UNKNOWN = 0;
    const SOURCE_TYPE_DISCORD = 1;

    const SOURCE_TYPES = [
        self::SOURCE_TYPE_UNKNOWN => 'unknown',
        self::SOURCE_TYPE_DISCORD => 'discord',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $sourceType = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $sourcePath = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $author = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleted = false;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="externalImages")
     */
    private ?Event $event = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceType(): ?int
    {
        return $this->sourceType;
    }

    public function setSourceType(int $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    public function getSourcePath(): ?string
    {
        return $this->sourcePath;
    }

    public function setSourcePath(?string $sourcePath): self
    {
        $this->sourcePath = $sourcePath;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getSourceTypeAsString(): string
    {
        if (isset(self::SOURCE_TYPES[$this->getSourceType()])) {
            return self::SOURCE_TYPES[$this->getSourceType()];
        }

        return 'inconnue';
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
}
