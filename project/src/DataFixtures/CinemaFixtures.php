<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use App\Entity\Country;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CinemaFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const CINEMA = [
        [
            'name' => 'Иван Васильевич меняет профессию',
            'playbackTime' => '01:33:00',
            'description' => 'Советская фантастическая комедия 1973 года, снятая режиссёром Леонидом Гайдаем по мотивам пьесы Михаила Булгакова «Иван Васильевич». Фильм рассказывает об инженере-изобретателе Шурике, создавшем машину времени, которая открывает двери в XVI век — во времена Ивана Васильевича Грозного, в результате чего царь оказывается в советской Москве, а его тёзка — управдом Иван Васильевич Бунша вместе с вором-рецидивистом Жоржем Милославским — в палатах царя.',
            'countries' => ['Россия', 'Франция'],
            ],
        [
            'name' => 'Джентльмены удачи',
            'playbackTime' => '01:28:00',
            'description' => 'Советская фантастическая комедия 1973 года, снятая режиссёром Леонидом Гайдаем по мотивам пьесы Михаила Булгакова «Иван Васильевич». Фильм рассказывает об инженере-изобретателе Шурике, создавшем машину времени, которая открывает двери в XVI век — во времена Ивана Васильевича Грозного, в результате чего царь оказывается в советской Москве, а его тёзка — управдом Иван Васильевич Бунша вместе с вором-рецидивистом Жоржем Милославским — в палатах царя.',
            'countries' => ['Индия', 'США'],
        ],
        [
            'name' => 'Бриллиантовая рука',
            'playbackTime' => '01:40:00',
            'description' => 'Советская фантастическая комедия 1973 года, снятая режиссёром Леонидом Гайдаем по мотивам пьесы Михаила Булгакова «Иван Васильевич». Фильм рассказывает об инженере-изобретателе Шурике, создавшем машину времени, которая открывает двери в XVI век — во времена Ивана Васильевича Грозного, в результате чего царь оказывается в советской Москве, а его тёзка — управдом Иван Васильевич Бунша вместе с вором-рецидивистом Жоржем Милославским — в палатах царя.',
            'countries' => ['США', 'Франция'],
            ],
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createEntity(Cinema::class, 3, function (Cinema $cinema, $count) {
            $countries = [];
            for ($i = 0; $i < 5; $i++) {
                $countries[] = $this->getReference("Country_$i");
            }

            $cinema
                ->setName(self::CINEMA[$count]['name'])
                ->setPlaybackTime(self::CINEMA[$count]['playbackTime'])
                ->setDescription(self::CINEMA[$count]['description']);

            array_map(function (Country $country) use ($count, $cinema) {
                if (in_array($country->getName(), self::CINEMA[$count]['countries'])) {
                    $cinema->addCountry($country);
                }
            }, $countries);

        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdminFixtures::class,
            CountryFixtures::class,
        ];
    }
}
