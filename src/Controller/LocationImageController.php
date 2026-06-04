<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\LocationImage;
use App\Service\LocationImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LocationImageController extends AbstractController
{
    public function __construct(
        private LocationImageService $imageService,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/api/locations/{id}/images', name: 'location_image_upload', methods: ['POST'])]
    public function upload(
        Location $location,
        Request $request,
    ): JsonResponse {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file provided'], 400);
        }

        try {
            $result = $this->imageService->upload($file);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
        
        $position = count($location->getLocationImages());

        $image = new LocationImage();
        $image->setLocation($location);
        $image->setFilename($result['filename']);
        $image->setMimeType($result['mimeType']);
        $image->setPosition($position);

        $this->em->persist($image);
        $this->em->flush();

        return new JsonResponse(['filename' => $result['filename']], 201);
    }
}