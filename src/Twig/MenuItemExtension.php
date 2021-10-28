<?php

namespace App\Twig;

use App\Entity\Menu\Item;
use App\Entity\User;
use App\Security\Restriction;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MenuItemExtension extends AbstractExtension
{
    private Router $router;
    private Restriction $restriction;

    public function __construct(RouterInterface $router, Restriction $restriction)
    {
        $this->router = $router;
        $this->restriction = $restriction;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('menu_item_href', [$this, 'itemHref']),
            new TwigFilter('menu_item_target', [$this, 'itemTarget']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('menu_item_is_visible', [$this, 'itemIsVisible']),
        ];
    }

    public function itemHref(Item $item)
    {
        switch ($item->getType()) {
            case Item::TYPE_LINK:
                return null === $item->getLink() ? '' : $item->getLink();
            case Item::TYPE_URL:
                return null === $item->getUrl() ? '' : '/' . $item->getUrl()->getSlug();
            case Item::TYPE_PAGE:
                return null === $item->getPage() ? '' : '/' . $item->getPage()->getPath();
            case Item::TYPE_OFFICE:
                return $this->router->generate('office');
            case Item::TYPE_SERVERS:
                return $this->router->generate('perun_index');
            case Item::TYPE_ROSTER:
                return $this->router->generate('roster');
            case Item::TYPE_CALENDAR:
                return $this->router->generate('calendar');
            case Item::TYPE_MISSION_MAKER:
                return $this->router->generate('mission_maker');
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

    public function itemIsVisible(?User $user, Item $item)
    {
        return $item->isEnabled() && $this->restriction->isGrantedToLevel($user, $item->getRestriction());
    }
}
