<?php

namespace App\Controller;

use App\Entity\ExternalImage;
use App\Entity\User;
use App\Form\ExternalImageType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/external/image")
 */
class ExternalImageController extends AbstractController
{
    /**
     * @Route("", name="external_image_add", methods={"POST"})
     *
     * To test this call (with fixtures):
     *
     * curl -X POST http://veaf.localhost/external/image -H 'Content-Type: application/json' -d '{"channel": "test", "author": "Mitch#4307", "url": "https://loremflickr.com/1920/1080?lock=123"}'
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        try {
            $data = \json_decode($request->getContent(), true);
            if (!is_array($data)) {
                throw new \Exception('invalid json content in request');
            }
            $data += [
                'author' => null,
                'url' => null,
                'channel' => 'undefined channel'
            ];

            $formData = [
                'author' => $data['author'],
                'url' => $data['url'],
            ];

            $externalImage = new ExternalImage();
            $externalImage->setCreatedAt(new \DateTime('now'));
            $externalImage->setSourceType(ExternalImage::SOURCE_TYPE_DISCORD); // force discord now
            $externalImage->setSourcePath($data['channel']); // for discord, path is discord channel

            $form = $this->createForm(ExternalImageType::class, $externalImage);
            $form->submit($formData);
            if (!$form->isValid()) {
                throw new \Exception(sprintf('invalid payload: %s', $form->getErrors(false, true)));
            }

            $user = $entityManager->getRepository(User::class)->findOneByDiscord($data['author']);
            if (null !== $user) {
                $externalImage->setEnabled(true);
                $externalImage->setUser($user);
            }

            $entityManager->persist($externalImage);
            $entityManager->flush();

            return new Response($serializer->serialize($externalImage, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user', 'event']]
            ));
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/list", name="external_image_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function list(Request $request, EntityManagerInterface $entityManager, RouterInterface $router): Response
    {
        return $this->render('external-image/list.html.twig',
            [
                'page_url' => $router->generate('external_image_list_page'),
            ]);
    }

    /**
     * @Route("/list/{page}", name="external_image_list_page", defaults={"page" = "0"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function listPage(Request $request, EntityManagerInterface $entityManager, int $page = 0): Response
    {
        $pageSize = 10;
        $firstResult = $page * $pageSize;

        $qb = $entityManager->getRepository(ExternalImage::class)->createQueryBuilder('e')
            ->andWhere('e.user = :user')->setParameter('user', $this->getUser())
            ->addOrderBy('e.createdAt', 'DESC')
            ->addOrderBy('e.id', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($pageSize);

        /** @var ExternalImage[]|Paginator $paginator */
        $paginator = new Paginator($qb);
        //$paginator->ge
        $results = [];
        foreach ($paginator as $result) {
            dump($result);
        }

        return $this->render('external-image/_list.html.twig', [
            'images' => $paginator,
        ]);
    }
}
