<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Cinema;
use App\Entity\Session;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SessionFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const SESSION = [
        'schema A',
        'schema B',
        'schema C',
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Session::class, 9, function (Session $session, $count) {
            $data = new DateTimeImmutable();
            /**@var Cinema $cinema */
            if ($count < 3) {
                $data = $data->modify('+0 day');
                list($cinema, $hall) = $this->setData($session, $count);
            } elseif ($count < 6) {
                $data = $data->modify('+1 day');
                list($cinema, $hall) = $this->setData($session, $count - 3);
            } else {
                $data = $data->modify('+2 day');
                list($cinema, $hall) = $this->setData($session, $count - 6);
            }

            /**@var Hall $hall */

            $session->setData($data);
            $session->setCinema($cinema);
            $session->setHall($hall);
	        $session->setPrices([30000, 50000]);
        });

        $manager->flush();
    }

    private function setData(Session $session, $count): array
    {
        $session->setSchema(self::SESSION[$count]);

        return [
            $this->getReference("Cinema_{$count}"),
            $this->getReference("Hall_{$count}"),
        ];
    }

    public function getDependencies(): array
    {
        return [
            HallFixtures::class,
            CinemaFixtures::class,
        ];
    }
}
