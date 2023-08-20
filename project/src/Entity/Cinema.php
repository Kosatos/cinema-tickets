<?php

namespace App\Entity;

use App\Repository\CinemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CinemaRepository::class)]
class Cinema
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

    #[Assert\Time()]
    #[ORM\Column(type: 'string', length: 255)]
    private string $playbackTime;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'cinema', targetEntity: MediaGallery::class, cascade: ['persist'])]
    private ?Collection $gallery;

    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'cinemas', cascade: ['persist'])]
    private ?Collection $countries;

    #[ORM\OneToMany(mappedBy: 'cinema', targetEntity: Session::class, cascade: ['persist'])]
    private ?Collection $sessions;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->sessions = new ArrayCollection();
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

    public function getPlaybackTime(): ?string
    {
        return $this->playbackTime;
    }

    public function setPlaybackTime(string $playbackTime): self
    {
        $this->playbackTime = $playbackTime;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, MediaGallery>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(MediaGallery $gallery): self
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery[] = $gallery;
            $gallery->setCinema($this);
        }

        return $this;
    }

    public function removeGallery(MediaGallery $gallery): self
    {
        if ($this->gallery->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getCinema() === $this) {
                $gallery->setCinema(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getCountries(): Collection|null
    {
        return $this->countries;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries[] = $country;
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        $this->countries->removeElement($country);

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setCinema($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getCinema() === $this) {
                $session->setCinema(null);
            }
        }

        return $this;
    }
}
