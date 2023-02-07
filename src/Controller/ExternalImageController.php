<?php

namespace App\Controller;

use App\Entity\ExternalImage;
use App\Form\ExternalImageType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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

            $entityManager->persist($externalImage);
            $entityManager->flush();

            return new Response($serializer->serialize($externalImage, 'json'));
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
