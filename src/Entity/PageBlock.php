<?php

namespace App\Entity;

use App\Repository\PageBlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageBlockRepository::class)
 */
class PageBlock
{
    const TYPE_NONE = 0;
    const TYPE_MARKDOWN = 1;

    const TYPES = [
        self::TYPE_NONE => 'aucun',
        self::TYPE_MARKDOWN => 'markdown',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="blocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Page $page;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $type;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $content;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $number;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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
}
