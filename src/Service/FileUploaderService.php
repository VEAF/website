<?php

namespace App\Service;

use App\Entity\File;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderService
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(UploadedFile $uploadedFile, ?User $user): File
    {
        $file = $this->fileService->create();

        $file->setOriginalName(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
        $file->setExtension($uploadedFile->guessExtension());
        $file->setSize($uploadedFile->getSize());
        $file->setMimeType($uploadedFile->getMimeType());
        $file->setOwner($user);

        try {
            $uploadedFile->move($this->fileService->getFileDirectory($file), $this->fileService->getStoredFilename($file));
        } catch (FileException $e) {
            throw $e;
        }

        $this->fileService->save($file);

        return $file;
    }
}
