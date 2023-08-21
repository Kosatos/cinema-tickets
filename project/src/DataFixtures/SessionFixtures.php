<?php

namespace App\DataFixtures;

use App\Entity\Session;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SessionFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Session::class, 14, function (Session $session, $count) {
            $data = new DateTimeImmutable('now');

            if ($count < 7) {
                $data = $data->modify('+' . $count . ' day');
                $session->setSchema('schema A');
	            $cinema = $this->getReference('Cinema_1');
            } else {
                $data = $data->modify('+' . $count - 7 . ' day');
                $session->setSchema('schema B');
	            $cinema = $this->getReference('Cinema_2');
            }

            $session->setData($data);
			$session->setCinema($cinema);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CinemaFixtures::class,
        ];
    }
}
