<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/upload", name="file_upload")
     * @IsGranted("ROLE_USER")
     */
    public function upload(): Response
    {
        // @todo WIP
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }
}
