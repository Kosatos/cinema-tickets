<?php

namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HallRepository::class)]
class Hall
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $number;

    #[ORM\OneToMany(mappedBy: 'hall', targetEntity: Session::class, cascade: ['persist', 'remove'])]
    private ?Collection $sessions;

    #[ORM\OneToMany(mappedBy: 'hall', targetEntity: Seat::class, cascade: ['persist', 'remove'])]
    private ?Collection $seats;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "Зал #{$this->number}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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
            $session->setHall($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getHall() === $this) {
                $session->setHall(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setHall($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getHall() === $this) {
                $seat->setHall(null);
            }
        }

        return $this;
    }

    public function getCountRows(): int
    {
        $rowCount = 0;
        array_map(function (Seat $seat) use (&$rowCount) {
            if ($seat->getIdentifier()[0] > $rowCount) {
                $rowCount = $seat->getIdentifier()[0];
            }
        }, $this->getSeats()->toArray());

        return $rowCount;
    }
}
