<?php

namespace App\Entity;

use App\Entity\Trait\HasMediaTrait;
use App\Repository\MediaGalleryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaGalleryRepository::class)]
class MediaGallery
{
    use HasMediaTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $type = 0;

    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ['persist'])]
    private ?Media $image = null;

    #[ORM\ManyToOne(targetEntity: Cinema::class, cascade: ['persist'], inversedBy: 'gallery')]
    private ?Cinema $cinema;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->type ?? $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }

    //    Виртуальное свойство для EasyAdmin для загрузки новых изображений
    public function getNewImage(): ?Media
    {
        return $this->image;
    }

    public function setNewImage(?Media $input_img): self
    {
        $this->uploadNewMedia($input_img, 'image');

        return $this;
    }

}
