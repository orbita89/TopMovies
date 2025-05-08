<?php

namespace App\Service;

use App\Exception\UploadFileInvalidException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadService
{

    private const LINK_BOOK_PATTERN = '/upload/books/%d/%s';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $uploadDir
    ) {
    }

    public function uploadBookFile(int $bookId, UploadedFile $file): string
    {
        $extension = $file->guessExtension();

        if (null === $extension) {
            throw new UploadFileInvalidException();
        }
        $unique = Uuid::v4()->toRfc4122().'.'.$extension;

        $file->move($this->uploadFile($bookId), $unique);

        return sprintf(self::LINK_BOOK_PATTERN, $bookId, $unique);
    }

    public function deleteFile(?string $getImage, string $fileName): void
    {
        $this->filesystem->remove($this->uploadFile($getImage).DIRECTORY_SEPARATOR.$fileName);
    }

    private function uploadFile(int $id): string
    {
        return $this->uploadDir.DIRECTORY_SEPARATOR.'books'.DIRECTORY_SEPARATOR.$id;
    }
}
