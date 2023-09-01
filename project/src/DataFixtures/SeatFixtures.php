<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Seat;
use App\Entity\Session;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeatFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const SCHEMA = [
        [1, 1], [1, 2], [1, 3], [1, 4],
        [2, 1], [2, 2], [2, 3], [2, 4],
        [3, 1], [3, 2], [3, 3], [3, 4],
        [4, 1], [4, 2], [4, 3], [4, 4],
    ];

    private const VIP = [[2, 2], [2, 3], [3, 2], [3, 3]];

    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Seat::class, 48, function (Seat $seat, $count) {
            if ($count < 16) {
                $this->setData($seat, $count, 0);
            } elseif ($count < 32) {
                $this->setData($seat, $count - 16, 1);
            } else {
                $this->setData($seat, $count - 32, 2);
            }
        });

        $manager->flush();
    }

    private function setData(Seat $seat, int $count, int $hallNumber): void
    {
        /**@var Hall $hall */
        $hall = $this->getReference("Hall_$hallNumber");
        $currentSeat = self::SCHEMA[$count];

        $seat->setIdentifier($currentSeat);
        $seat->setIsVip(in_array($currentSeat, self::VIP));
        $seat->setHall($hall);
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
        ];
    }
}
