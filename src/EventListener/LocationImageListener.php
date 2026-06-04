<?php

namespace App\EventListener;

use App\Entity\LocationImage;
use App\Service\LocationImageService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postRemove, entity: LocationImage::class)]
class LocationImageListener
{
    public function __construct(
        private LocationImageService $imageService,
    ) {}

    public function postRemove(LocationImage $image): void
    {
        $this->imageService->delete($image->getFilename());
    }
}