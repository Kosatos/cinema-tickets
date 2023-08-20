<?php

namespace App\DataFixtures;

use App\Entity\Session;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Session::class, 14, function (Session $session, $count) {
            $data = new DateTimeImmutable('now');

            if ($count < 7) {
                $data = $data->modify('+' . $count . ' day');
            } else {
                $data = $data->modify('+' . $count - 7 . ' day');
            }

            $session
                ->setData($data);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SessionSchemaFixtures::class,
        ];
    }
}
