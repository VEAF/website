<?php

namespace App\Manager;

use App\Entity\Page;
use App\Entity\PageBlock;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PageManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveBlock(PageBlock $block)
    {
        $this->em->persist($block);
        $page = $block->getPage();

        // add block to first position
        if ($block->getNumber() <= 1) {
            $block->setNumber(1);
        }
        $page->addBlock($block);

        foreach ($page->getBlocks() as $orderBlock) {
            if ($orderBlock->getId() != $block->getId()) {
                if ($orderBlock->getNumber() >= $block->getNumber()) {
                    $orderBlock->setNumber($orderBlock->getNumber() + 1);
                }
            }
        }

        $this->normalizeBlockNumber($page);

        $this->save($page, true, true);
    }

    public function getOrderedBlocks(Page $page)
    {
        // order collection items by position property
        $orderBy = (Criteria::create())->orderBy([
            'number' => Criteria::ASC,
        ]);

        // return sorter SomeCollectionItem array
        return $page->getBlocks()->matching($orderBy)->toArray();
    }

    public function normalizeBlockNumber(Page $page)
    {
        $number = 1;
        foreach ($this->getOrderedBlocks($page) as $block) {
            $block->setNumber($number++);
        }
    }

    public function save(Page $page, bool $markUpdated = true, bool $flush = false)
    {
        $this->em->persist($page);

        if (null === $page->getCreatedAt()) {
            $page->setCreatedAt(new \DateTime('now'));
        }
        if ($markUpdated) {
            $page->setUpdatedAt(new \DateTime('now'));
        }

        if ($flush) {
            $this->em->flush();
        }
    }
}
