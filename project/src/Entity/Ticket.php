<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Ticket
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Seat::class, cascade: ['persist'])]
    private ?Seat $seat;

    #[ORM\ManyToOne(targetEntity: Session::class, cascade: ['persist'], inversedBy: 'tickets')]
    private ?Session $session;

    #[ORM\Column(type: 'string', length: 255)]
    private string $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $seat = $this->getSeat()->getIdentifier();
        $session = $this->getSession();

        $code = $seat[0].$seat[1].$session->getHall().$session->getData()->format('Y-m-d H:i:s').$session->getCinema()->getName();
        $this->code = md5($code);
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
