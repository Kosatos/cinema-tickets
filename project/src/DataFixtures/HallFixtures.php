<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Session;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HallFixtures extends BaseFixture /* implements DependentFixtureInterface */
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Hall::class, 2, function (Hall $hall, $count) {
            $hall->setNumber($count + 1);
        });

        $manager->flush();
    }

//    public function getDependencies(): array
//    {
//        return [
////            SessionFixtures::class,
//        ];
//    }
}
