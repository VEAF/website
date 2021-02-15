<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    const TYPE_UNKNOWN = 0;
    const TYPE_IMAGE = 1;
    const TYPE_PDF = 2;

    const TYPES = [
        self::TYPE_UNKNOWN => 'inconu',
        self::TYPE_IMAGE => 'image',
        self::TYPE_PDF => 'pdf',
    ];

    const MIME_TYPES = [
        'application/pdf' => self::TYPE_PDF,
        'image/jpg' => self::TYPE_IMAGE,
        'image/jpeg' => self::TYPE_IMAGE,
        'image/png' => self::TYPE_IMAGE,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var UuidInterface
     * @ORM\Column(type="uuid")
     */
    private $uuid = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $type = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $mimeType;

    /**
     * @ORM\Column(type="integer")
     */
    private int $size;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?User $owner = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $originalName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeAsString(): string
    {
        if (isset(self::TYPES[$this->type])) {
            return self::TYPES[$this->type];
        }

        return self::TYPES[self::TYPE_UNKNOWN];
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        if (null === $this->type) {
            $this->setType($this->getTypeFromMimeType());
        }

        return $this;
    }

    public function getTypeFromMimeType(): int
    {
        if (isset(self::MIME_TYPES[$this->mimeType])) {
            return self::MIME_TYPES[$this->mimeType];
        }

        return self::TYPE_UNKNOWN;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function isImage(): bool
    {
        return self::TYPE_IMAGE === $this->type;
    }
}
