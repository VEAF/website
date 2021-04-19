<?php

namespace App\Controller;

use App\Entity\File;
use App\Service\FileService;
use App\Service\FileUploaderService;
use Gregwar\ImageBundle\Services\ImageHandling;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/thumb/{file}/{width}/{height}", name="file_thumb", methods={"GET"})
     * @ParamConverter("file", options={"mapping": {"file": "uuid"}})
     */
    public function thumb(File $file, int $width = null, int $height = null, FileService $fileService, ImageHandling $imageHandling): Response
    {
        $filePath = $fileService->getFilePath($file);
        $image = $imageHandling->open($filePath);
        if (null !== $width || null != $height) {
            $image->cropResize($width, $height);
        }
        $cachePath = $image->jpeg();

        return new Response('', Response::HTTP_FOUND, ['Location' => $cachePath]);
    }

    /**
     * @Route("/upload", name="file_upload", methods={"POST"})
     * @Route("/upload/{objectName}/{objectId}", name="file_upload_object", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function upload(Request $request, string $objectName = null, string $objectId = null, FileUploaderService $uploaderService): Response
    {
        if ($request->files->get('image')) {
            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $request->files->get('image');
            if ($uploadedImage) {
                $image = $uploaderService->upload($uploadedImage, $this->getUser(), $objectName, $objectId);
                $data = ['data' => ['filePath' => ltrim($this->generateUrl('file_thumb', ['file' => $image->getUuid(), 'width' => 1400]),'/')]];

                return new Response(json_encode($data));
            }
        }

        throw new \Exception('error');
    }
}
