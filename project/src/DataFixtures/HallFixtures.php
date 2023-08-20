<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Session;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HallFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Hall::class, 2, function (Hall $hall, $count) {
            $hall->setNumber($count + 1);

            $sessions = [];
            if ($count == 0) {
                for ($i = 0; $i < 7; $i++) {
                    $sessions[] = $this->getReference("Session_$i");
                }
            } elseif ($count == 1) {
                for ($i = 7; $i < 14; $i++) {
                    $sessions[] = $this->getReference("Session_$i");
                }
            }

            array_map(fn(Session $session) => $hall->addSession($session), $sessions);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
        ];
    }
}
