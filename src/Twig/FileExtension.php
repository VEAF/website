<?php

namespace App\Twig;

use App\Entity\File;
use App\Service\FileService;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FileExtension extends AbstractExtension
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('file_path', [$this, 'filePath']),
        ];
    }

    public function filePath(File $file)
    {
        return $this->fileService->getFilePath($file);
    }
}