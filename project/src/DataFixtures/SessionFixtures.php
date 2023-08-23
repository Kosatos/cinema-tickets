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
    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Session::class, 7, function (Session $session, $count) {
            $data = new DateTimeImmutable('now');

			/**@var Cinema $cinema*/

            if ($count < 2) {
                $data = $data->modify('+' . $count . ' day');
                $session->setSchema('schema A');
	            $cinema = $this->getReference("Cinema_0");
            } elseif ($count < 5) {
                $data = $data->modify('+' . $count - 5 . ' day');
                $session->setSchema('schema B');
	            $cinema = $this->getReference("Cinema_1");
            } else {
	            $data = $data->modify('+' . $count - 7 . ' day');
	            $session->setSchema('schema B');
				$cinema = $this->getReference("Cinema_2");
            }

			/**@var Hall $hall*/
	        $hallNumber = random_int(0,1);
			$hall = $this->getReference("Hall_{$hallNumber}");
            $session->setData($data);
			$session->setCinema($cinema);
			$session->setHall($hall);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
			HallFixtures::class,
            CinemaFixtures::class,
        ];
    }
}
