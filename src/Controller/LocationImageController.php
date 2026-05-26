<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\LocationImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LocationImageController extends AbstractController
{
    #[Route('/api/locations/{id}/images', name: 'location_image_upload', methods: ['POST'])]
    public function upload(
        Location $location,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file provided'], 400);
        }

        $mimeType = $file->getMimeType();
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return new JsonResponse(['error' => 'Invalid file type'], 400);
        }

        $filename = uniqid() . '.' . $file->guessExtension();
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/locations';
        $position = count($location->getLocationImages());

        $file->move($uploadDir, $filename);

        $image = new LocationImage();
        $image->setLocation($location);
        $image->setFilename($filename);
        $image->setMimeType($mimeType);
        $image->setPosition($position);

        $em->persist($image);
        $em->flush();

        return new JsonResponse(['filename' => $filename], 201);
    }
}