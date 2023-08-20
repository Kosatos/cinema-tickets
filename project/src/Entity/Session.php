<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
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

    #[ORM\ManyToOne(targetEntity: Session::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Session $session;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Session::class, cascade: ['persist', 'remove'])]
    private ?Collection $sessions;

    #[ORM\ManyToOne(targetEntity: Hall::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Hall $hall;

    #[ORM\ManyToOne(targetEntity: Cinema::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Cinema $cinema;

    private const SCHEMA_A = 'schema A';
    private const SCHEMA_B = 'schema B';
    private const SCHEMA_C = 'schema C';
    private const SESSION_SCHEMA = [
        'schema A' => [
            '10:00:00',
            '12:00:00',
            '14:00:00',
            '16:00:00',
            '18:00:00',
            '20:00:00',
        ],
        'schema B' => [
            '10:00:00',
            '11:30:00',
            '13:00:00',
            '14:30:00',
            '16:00:00',
            '17:30:00',
            '19:00:00',
        ],
        'schema C' => [
            '10:00:00',
            '13:00:00',
            '16:00:00',
            '19:30:00',
        ],
    ];

    #[ArrayShape([
        'схема A' => 'string',
        'схема B' => 'string',
        'схема C' => 'string',
    ])]
    public static function getAvailavleSchemaName(): array
    {
        return [
            'схема A' => self::SCHEMA_A,
            'схема B' => self::SCHEMA_B,
            'схема C' => self::SCHEMA_C,
        ];
    }

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($data = $this->data) {
            $dateTime = new DateTime("@{$data->getTimeStamp()}");

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

    public function setData(?DateTimeImmutable $data): self
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
     * @return Collection|null
     */
    public function getSessions(): ?Collection
    {
        return $this->sessions;
    }

    public function addSession(?self $session): self
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

    public ?string $schema = null;

    public function getSchema(): ?string
    {
        return $this->schema;
    }
    public function setSchema(?string $sessionScheema): self
    {
        if (key_exists($sessionScheema, self::SESSION_SCHEMA)) {
            array_map(function ($time) {
                $newSession = new Session();
                $newSession->setStartedAt($time);

                $this->addSession($newSession);

            }, self::SESSION_SCHEMA[$sessionScheema]);

            return $this;
        }

        return $this;
    }
}
