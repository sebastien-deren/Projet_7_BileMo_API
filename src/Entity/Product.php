<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Serializer\XmlRoot("product")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href= "expr('api/products/' ~ object.getId())",
 *     exclusion = @Hateoas\Exclusion(groups="details"))
 *
 * @Hateoas\Relation(
 *     "list",
 *     href="api/products",
 *     exclusion= @Hateoas\Exclusion(groups="details"))
 */
#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list','details'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list','details'])]
    private ?string $name = null;

    #[Groups(['list','details'])]
    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[Groups(['list','details'])]
    #[ORM\Column(length: 255)]
    private ?string $operatingSystem = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['details'])]

    private ?int $screensize = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['details'])]
    private ?int $numberOfPhoto = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['details'])]
    private ?string $resolution = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['details'])]
    private ?int $photoResolution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getScreenSize(): ?int
    {
        return $this->screensize;
    }

    public function setScreenSize(?int $screenSize): self
    {
        $this->screensize = $screenSize;

        return $this;
    }

    public function getNumberOfPhoto(): ?int
    {
        return $this->numberOfPhoto;
    }

    public function setNumberOfPhoto(?int $numberOfPhoto): self
    {
        $this->numberOfPhoto = $numberOfPhoto;

        return $this;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function setResolution(?string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function getPhotoResolution(): ?int
    {
        return $this->photoResolution;
    }

    public function setPhotoResolution(?int $photoResolution): self
    {
        $this->photoResolution = $photoResolution;

        return $this;
    }
}
