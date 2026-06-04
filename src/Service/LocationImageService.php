<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocationImageService
{
    private array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

    private string $uploadDir;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    ) {
        $this->uploadDir = $projectDir . '/public/uploads/locations/';
    }

    /**
     * Uploads a file and returns its filename and mime type.
     *
     * @return array [filename, mimeType]
     * 
     * @throws \InvalidArgumentException if the mime type is not allowed
     */
    public function upload(UploadedFile $file): array
    {
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid mime type: ' . $mimeType);
        }
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($this->uploadDir, $filename);
        
        return [
            'filename' => $filename,
            'mimeType' => $mimeType,
        ];
    }

    /**
     * Deletes a file from the uploads directory.
     */
    public function delete(string $filename): void
    {
        $path = $this->uploadDir . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}