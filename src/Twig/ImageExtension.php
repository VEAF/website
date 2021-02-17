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

class ImageExtension extends AbstractExtension
{
    private FileService $fileService;
    private string $projectDir;

    const IMAGE_FALLBACK = 'public/img/crew_blur.jpg';
    const IMAGE_HEADER_FALLBACK = 'public/img/f5e_header_blur.jpg';

    public function __construct(FileService $fileService, string $projectDir)
    {
        $this->fileService = $fileService;
        $this->projectDir = $projectDir;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('image_path', [$this, 'imagePath']),
            new TwigFunction('image_header_path', [$this, 'imageHeaderPath']),
        ];
    }

    public function imagePath(?File $file, string $fallBack = self::IMAGE_FALLBACK)
    {
        if (null === $file) {
            return $this->projectDir . '/' . $fallBack;
        }

        return $this->fileService->getFilePath($file);
    }

    public function imageHeaderPath(?File $file, string $fallBack = self::IMAGE_HEADER_FALLBACK)
    {
        return $this->imagePath($file, $fallBack);
    }

}