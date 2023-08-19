<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\ManyToMany(targetEntity: Cinema::class, mappedBy: 'countries', cascade: ['persist'])]
    private ?Collection $cinemas;

    public function __construct()
    {
        $this->cinemas = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Cinema>
     */
    public function getCinemas(): Collection
    {
        return $this->cinemas;
    }

    public function addCinema(Cinema $cinema): self
    {
        if (!$this->cinemas->contains($cinema)) {
            $this->cinemas[] = $cinema;
            $cinema->addCountry($this);
        }

        return $this;
    }

    public function removeCinema(Cinema $cinema): self
    {
        if ($this->cinemas->removeElement($cinema)) {
            $cinema->removeCountry($this);
        }

        return $this;
    }
}
