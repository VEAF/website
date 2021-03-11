<?php

namespace App\Manager\Menu;

use App\Entity\Calendar\Event;
use App\Entity\Menu\Item;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Item $item, bool $flush = false)
    {
        $now = new \DateTime('now');

        switch ($item->getType()) {
            case Item::TYPE_URL:
                $item->setPage(null);
                $item->setLink(null);
                break;
            case Item::TYPE_PAGE:
                $item->setUrl(null);
                $item->setLink(null);
                break;
            case Item::TYPE_LINK:
                $item->setPage(null);
                $item->setUrl(null);
                break;
        }

        $this->em->persist($item);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function delete(Item $item, bool $flush = false)
    {
        $this->em->remove($item);

        if ($flush) {
            $this->em->flush();
        }
    }
}
