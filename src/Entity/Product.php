<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $operatingSystem = null;

    #[ORM\Column(nullable: true)]
    private ?int $screensize = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfPhoto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resolution = null;

    #[ORM\Column(nullable: true)]
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

    public function getScreensize(): ?int
    {
        return $this->screensize;
    }

    public function setScreensize(?int $screensize): self
    {
        $this->screensize = $screensize;

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
