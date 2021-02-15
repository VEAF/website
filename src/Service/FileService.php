<?php

namespace App\Service;

use App\Entity\File;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private EntityManager $entityManager;
    private string $dataDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
        $this->entityManager = $entityManager;
    }

    public function create(): File
    {
        $file = new File();
        $file->setUuid(Uuid::uuid4());
        $file->setCreatedAt(new \DateTime('now'));

        return $file;
    }

    public function save(File $file, bool $flush = true)
    {
        $this->entityManager->persist($file);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw $e;
        }

        return $fileName;
    }

    /**
     * Get file directory - the main directory where all files are stored
     *
     * Ex: /var/www/html/var/data/files
     */
    public function getDataDirectory(): string
    {
        return $this->dataDirectory;
    }

    /**
     * Get file relative directory - dynamic part to split files in many directories
     *
     * Ex: a/b
     */
    private function getRelativeDirectory(File $file): string
    {
        $uuid = $file->getUuid()->toString();

        // split files into 2 directories level (36*36 => 1296 directories)
        return $uuid[0] . '/' . $uuid[1];
    }

    /**
     * Get file directory - the directory where the file is stored
     *
     * Ex: /var/www/html/var/data/files/a/b
     */
    public function getFileDirectory(File $file): string
    {
        return $this->getDataDirectory() . '/' . $this->getRelativeDirectory($file);
    }

    /**
     * Get stored file name
     *
     * Ex: ab4680f3-8854-44c5-b804-1aa16cab6a04
     */
    public function getStoredFilename(File $file): string
    {
        return $file->getUuid()->toString();
    }

    /**
     * Get file path
     *
     * Ex: /var/www/html/var/data/files/a/b/ab4680f3-8854-44c5-b804-1aa16cab6a04
     */
    public function getFilePath(File $file): string
    {
        return $this->getFileDirectory($file) . '/' . $this->getStoredFilename($file);
    }
}