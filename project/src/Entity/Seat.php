<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\Count(
        min: 2,
        max: 2,
        minMessage: 'Вторым параметром укажите место в ряду',
        maxMessage: 'Первый элемент - ряд, второй - место. Остальные параметры избыточны.'
    )]
    #[ORM\Column(type: 'json')]
    private array $identifier = [];

    #[ORM\Column(type: 'boolean')]
    private bool $isVip;

    #[ORM\ManyToOne(targetEntity: Hall::class, cascade: ['persist'], inversedBy: 'seats')]
    private ?Hall $hall;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $seatsForCurrentHall = array_map(fn(Seat $seat) => $this->getIdentifier(), $this->getHall()->getSeats()->toArray());

        $object = array_filter($this->getHall()->getSeats()->toArray(), fn(Seat $seat) => $this != $seat && in_array($seat->getIdentifier(), $seatsForCurrentHall));

        if (count($object) > 0) {
            $context->buildViolation('Место с такими параметрами уже существует.')
                ->atPath('identifier')
                ->addViolation();
        }
    }

    public function __toString(): string
    {
        $seat = $this->identifier;

        return "Ряд - {$seat[0]}, место - {$seat[1]}.";
    }

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

     public function hasTicket(Session $session): bool
     {
         return (bool)$session->getTickets()->filter(fn(Ticket $ticket) => $ticket->getSeat() === $this)->current();
     }
}
