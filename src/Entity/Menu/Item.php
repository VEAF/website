<?php

namespace App\Entity\Menu;

use App\Entity\Page;
use App\Entity\Url;
use App\Repository\Menu\ItemRepository;
use App\Validator\Menu\Item\Type as TypeAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @ORM\Table(name="menu_item")
 * @TypeAssert
 */
class Item
{
    const TYPE_NONE = 0;
    const TYPE_MENU = 1;
    const TYPE_LINK = 2;
    const TYPE_URL = 3;
    const TYPE_PAGE = 4;
    const TYPE_DIVIDER = 5;
    const TYPE_OFFICE = 6;
    const TYPE_SERVERS = 7;
    const TYPE_ROSTER = 8;
    const TYPE_CALENDAR = 9;

    const TYPES = [
        self::TYPE_MENU => 'Menu',
        self::TYPE_LINK => 'Url personnalisée',
        self::TYPE_URL => 'Url (redirectrion)',
        self::TYPE_PAGE => 'Page',
        self::TYPE_DIVIDER => 'Séparateur',
        self::TYPE_OFFICE => 'Bureau',
        self::TYPE_SERVERS => 'Serveurs',
        self::TYPE_ROSTER => 'Roster',
        self::TYPE_CALENDAR => 'Calendrier',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $label;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $type;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $themeClasses;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled = false;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="items")
     */
    private ?Item $menu;

    /**
     * @var Item[]|ArrayCollection|array
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="menu")
     */
    private $items;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $position = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $link = null;

    /**
     * @ORM\ManyToOne(targetEntity=Url::class)
     */
    private ?Url $url = null;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class)
     */
    private ?Page $page = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getThemeClasses(): ?string
    {
        return $this->themeClasses;
    }

    public function setThemeClasses(?string $themeClasses): self
    {
        $this->themeClasses = $themeClasses;

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

    public function getMenu(): ?self
    {
        return $this->menu;
    }

    public function setMenu(?self $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(self $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setMenu($this);
        }

        return $this;
    }

    public function removeItem(self $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getMenu() === $this) {
                $item->setMenu(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
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

    public function getTypeAsString(): string
    {
        if (isset(self::TYPES[$this->type])) {
            return self::TYPES[$this->type];
        }

        return 'inconnu';
    }

    public function __toString()
    {
        return $this->label ?: '';
    }
}
