<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Filter\BoundsFilter;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            parameters: [
                ':property' => new QueryParameter(
                    properties: ['name', 'address', 'city', 'countryCode'],
                    filter: new PartialSearchFilter()
                ),
                'bounds' => new QueryParameter(
                    filter: new BoundsFilter()
                )
            ]
        ),
        new Post(),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['location:read']],
    denormalizationContext: ['groups' => ['location:write']],
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:read'])]
    private ?int $id = null;

    // --- Informations principales ---

    #[ORM\Column(length: 255)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $description = null;

    // --- Localisation ---

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['location:read', 'location:write'])]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['location:read', 'location:write'])]
    private ?float $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 2, nullable: true)]
    #[Groups(['location:read', 'location:write'])]
    private ?string $countryCode = null; // ISO 3166-1 alpha-2, ex: "FR"

    // --- Tags ---

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['location:read', 'location:write'])]
    private array $tags = [];

    // --- Références externes ---

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['location:read', 'location:write'])]
    private array $externalRefs = [];
    // Exemple: ["google_place_id" => "ChIJ...", "wikidata_id" => "Q243", "osm_node_id" => "123456"]

    // --- Timestamps ---

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['location:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['location:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: LocationImage::class, mappedBy: 'Location', orphanRemoval: true)]
    #[SerializedName('images')]
    #[Groups(['location:read'])]
    private Collection $locationImages;

    public function __construct()
    {
        $this->locationImages = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $currentDate = new \DateTimeImmutable();
        $this->createdAt = $currentDate;
        $this->updatedAt = $currentDate;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // --- Getters / Setters ---

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }
    public function setName(string $name): static {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }
    public function setDescription(?string $description): static {
        $this->description = $description;
        return $this;
    }

    public function getLatitude(): ?float {
        return $this->latitude;
    }
    public function setLatitude(float $latitude): static {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float {
        return $this->longitude;
    }
    public function setLongitude(float $longitude): static {
        $this->longitude = $longitude;
        return $this;
    }

    public function getAddress(): ?string {
        return $this->address;
    }
    public function setAddress(?string $address): static {
        $this->address = $address;
        return $this;
    }

    public function getCity(): ?string {
        return $this->city;
    }
    public function setCity(?string $city): static {
        $this->city = $city;
        return $this;
    }

    public function getCountryCode(): ?string {
        return $this->countryCode;
    }
    public function setCountryCode(?string $countryCode): static {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getTags(): array {
        return $this->tags;
    }
    public function setTags(array $tags): static {
        $this->tags = $tags;
        return $this;
    }

    public function getExternalRefs(): array {
        return $this->externalRefs;
    }
    public function setExternalRefs(array $externalRefs): static {
        $this->externalRefs = $externalRefs;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, LocationImage>
     */
    public function getLocationImages(): Collection
    {
        return $this->locationImages;
    }

    public function addLocationImage(LocationImage $locationImage): static
    {
        if (!$this->locationImages->contains($locationImage)) {
            $this->locationImages->add($locationImage);
            $locationImage->setLocation($this);
        }

        return $this;
    }

    public function removeLocationImage(LocationImage $locationImage): static
    {
        if ($this->locationImages->removeElement($locationImage)) {
            // set the owning side to null (unless already changed)
            if ($locationImage->getLocation() === $this) {
                $locationImage->setLocation(null);
            }
        }

        return $this;
    }
}