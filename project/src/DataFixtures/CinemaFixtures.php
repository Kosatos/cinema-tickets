<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CinemaFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const CINEMA = [
        [
            'name' => 'Иван Васильевич меняет профессию',
            'playbackTime' => '01:33:00',
        ],
        [
            'name' => 'Джентльмены удачи',
            'playbackTime' => '01:28:00',
        ],
        [
            'name' => 'Бриллиантовая рука',
            'playbackTime' => '01:40:00',
        ],
    ];

    public function loadData(ObjectManager $manager): void
    {

        $this->createEntity(Cinema::class, 3, function (Cinema $cinema, $count) {
            $cinema
                ->setName(self::CINEMA[$count]['name'])
                ->setPlaybackTime(self::CINEMA[$count]['playbackTime']);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdminFixtures::class,
        ];
    }
}
