<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JetBrains\PhpStorm\ArrayShape;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $data = null;

    #[Gedmo\Slug(fields: ['data'])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $slug;

    #[ORM\ManyToOne(targetEntity: Hall::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Hall $hall;

    #[ORM\ManyToOne(targetEntity: Cinema::class, cascade: ['persist'], inversedBy: 'sessions')]
    private ?Cinema $cinema;

    public ?string $schema = null;

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
            '19:00:00',
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

    public function __toString(): string
    {
        if ($data = $this->data) {
            $dateTime = new DateTime("@{$data->getTimeStamp()}");

            return $dateTime->format('d:m:Y H:i');
        }

        return 'сеанс';
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function setSchema(?string $sessionSchema): self
    {
        $this->schema = $sessionSchema;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getObjectManager();

        if ($schema = $this->schema) {
            if (array_key_exists($schema, self::SESSION_SCHEMA)) {

                array_map(function (string $time) use ($em) {
                    $dataFormat = "{$this->data->format('Y-m-d')} {$time}";

                    $session = new Session();
                    $session->setCinema($this->cinema);
                    $session->setHall($this->hall);
                    $session->setData(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dataFormat));

                    $em->persist($session);
                }, self::SESSION_SCHEMA[$schema]);
            }

            $this->setData(null);
            $this->setHall(null);
            $this->setCinema(null);
        }
    }
}
