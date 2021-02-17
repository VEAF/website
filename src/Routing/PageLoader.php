<?php

// src/Routing/ExtraLoader.php

namespace App\Routing;

use App\Controller\PageController;
use App\Entity\Page;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageLoader extends Loader
{
    private $isLoaded = false;

    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();

        // @todo improve performance by caching routes

        $pages = $this->entityManager->getRepository(Page::class)->findBy(['enabled' => true]);
        foreach ($pages as $page) {
            // prepare a new route
            $path = $page->getPath();
            $defaults = [
                '_controller' => PageController::class.'::page',
                'page' => $page->getId(),
            ];
            $requirements = [
                // example
                // 'parameter' => '\d+',
            ];
            $route = new Route($path, $defaults, $requirements);

            // add the new route to the route collection
            $routeName = 'page_'.$page->getRoute();
            $routes->add($routeName, $route);
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'page' === $type;
    }
}
