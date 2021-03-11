<?php

namespace App\Twig;

use App\Entity\Calendar\Event;
use App\Entity\Menu\Item;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MenuItemExtension extends AbstractExtension
{
    private Router $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('menu_item_href', [$this, 'itemHref']),
            new TwigFilter('menu_item_target', [$this, 'itemTarget']),
        ];
    }

    public function itemHref(Item $item)
    {
        switch ($item->getType()) {
            case Item::TYPE_LINK:
                return null === $item->getLink() ? '' : $item->getLink();
            case Item::TYPE_URL:
                return null === $item->getUrl() ? '' : $item->getUrl()->getSlug();
            case Item::TYPE_PAGE:
                return null === $item->getPage() ? '' : $item->getPage()->getPath();
            case Item::TYPE_OFFICE:
                return $this->router->generate('office');
            case Item::TYPE_SERVERS:
                return $this->router->generate('perun_index');
            case Item::TYPE_ROSTER:
                return $this->router->generate('roster');
            case Item::TYPE_CALENDAR:
                return $this->router->generate('calendar');
            default:
                return '';
        }
    }

    public function itemTarget(Item $item)
    {
        switch ($item->getType()) {
            case Item::TYPE_LINK:
            case Item::TYPE_URL:
                return '_blank';
            default:
                return '';
        }
    }
}
