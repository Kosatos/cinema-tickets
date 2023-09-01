<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private array $identifier = [];

    #[ORM\Column(type: 'boolean')]
    private bool $isVip;

    #[ORM\ManyToOne(targetEntity: Hall::class, cascade:['persist'], inversedBy: 'seats')]
    private ?Hall $hall;

    public function __construct()
    {
        $this->isVip = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?array
    {
        return $this->identifier;
    }

    public function setIdentifier(array $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function isIsVip(): ?bool
    {
        return $this->isVip;
    }

    public function setIsVip(?bool $isVip): self
    {
        $this->isVip = $isVip;

        return $this;
    }

    public function isActive(): bool
    {
        return false;
    }

    public function getHall(): ?Hall
    {
        return $this->hall;
    }

    public function setHall(?Hall $hall): self
    {
        $this->hall = $hall;

        return $this;
    }
}
