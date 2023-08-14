<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
	private ObjectManager $manager;
	protected Generator $faker;

	abstract protected function loadData(ObjectManager $manager);

	public function load(ObjectManager $manager): void
	{
		$this->manager = $manager;
		$this->faker   = Factory::create();

		$this->loadData($manager);
	}

	protected function createEntity(string $className, int $count, callable $factory): void
	{
		for ($i = 0; $i < $count; $i++) {
			$entity = new $className();
			$factory($entity, $i);
			$this->manager->persist($entity);
			$class = explode('\\', $className);
			$this->addReference(end($class) . '_' . $i, $entity);
		}
	}
}