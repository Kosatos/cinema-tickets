<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Seat;
use App\Entity\Session;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeatFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const ITTERATOR = [1, 2, 3, 4];

    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Seat::class, 48, function (Seat $seat, $count) {
            $seatInHall = [];

            for ($i = 1; $i < 5; $i++) {
                for ($k = 1; $k < 5; $k++) {
                    $seatInHall[] = [$i, $k];
                }
            }

            if ($count < 16) {
                $this->setData($seat, $seatInHall, $count, 0);
            } elseif ($count < 32) {
                $this->setData($seat, $seatInHall, $count - 16, 1);
            } else {
                $this->setData($seat, $seatInHall, $count - 32, 2);
            }

        });

        $manager->flush();
    }

    private function setData(Seat $seat, array $seatInHall, int $count, int $hallNumber): void
    {
        $seat->setIdentifier($seatInHall[$count]);
        /**@var Hall $hall */
        $hall = $this->getReference("Hall_$hallNumber");
        $seat->setHall($hall);
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
        ];
    }
}
