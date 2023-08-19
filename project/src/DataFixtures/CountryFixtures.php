<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends BaseFixture
{
    private const COUNTRY_NAME = [
        'СССР',
        'Россия',
        'Франция',
        'США',
        'Индия',
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Country::class, 5, function (Country $country, $count) {
            $country
                ->setName(self::COUNTRY_NAME[$count]);
        });

        $manager->flush();
    }
}
