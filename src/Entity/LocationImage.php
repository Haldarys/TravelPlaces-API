<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\LocationImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: LocationImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            uriTemplate: '/locations/{id}/images',
            uriVariables: [
                'id' => new Link(fromClass: Location::class, toProperty: 'Location')
            ]
        ),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['location_image:read']],
    denormalizationContext: ['groups' => ['location_image:write']],
)]
class LocationImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location_image:read', 'location:read'])]
    private ?int $id = null;
    
    #[ORM\ManyToOne(inversedBy: 'locationImages')]
    #[ORM\JoinColumn(nullable: false)]
    #[SerializedName('location')]
    #[Groups(['location_image:read'])]
    private ?Location $Location = null;

    #[ORM\Column(length: 255)]
    #[Groups(['location_image:read', 'location:read'])]
    private ?string $filename = null;

    #[ORM\Column(length: 50)]
    #[Groups(['location_image:read', 'location:read'])]
    private ?string $mimeType = null;

    #[ORM\Column]
    #[Groups(['location_image:read', 'location_image:write', 'location:read'])]
    private ?int $position = null;

    #[ORM\Column]
    #[Groups(['location_image:read', 'location:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $currentDate = new \DateTimeImmutable();
        $this->createdAt = $currentDate;
    }

    // --- Getters / Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getLocation(): ?Location
    {
        return $this->Location;
    }

    public function setLocation(?Location $Location): static
    {
        $this->Location = $Location;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
