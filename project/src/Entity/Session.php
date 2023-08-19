<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $data = null;

    #[Assert\Time]
    #[ORM\Column(type: 'string', length: 8, nullable: true)]
    private ?string $started_at;

    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?self $session;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?Collection $sessions;

    #[ORM\ManyToOne(targetEntity: Hall::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Hall $hall;

    #[ORM\ManyToOne(targetEntity: Cinema::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Cinema $cinema;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->data) {
            $dateTime = new DateTime("@{$this->data->getTimeStamp()}");

            return $dateTime->format('d:m:Y');
        }

        return $this->started_at ?? 'сеанс';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?DateTimeImmutable
    {
        return $this->data;
    }

    public function setData(DateTimeImmutable $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getStartedAt(): ?string
    {
        return $this->started_at;
    }

    public function setStartedAt(?string $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getSession(): ?self
    {
        return $this->session;
    }

    public function setSession(?self $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(self $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setSession($this);
        }

        return $this;
    }

    public function removeSession(self $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getSession() === $this) {
                $session->setSession(null);
            }
        }

        return $this;
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

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }
}
