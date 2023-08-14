<?php

namespace App\DataFixtures;

use App\Entity\Admin;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AdminFixtures extends BaseFixture /* implements DependentFixtureInterface */
{
	public function __construct(private readonly UserPasswordHasherInterface $passwordEncoder)
	{
	}

	public function loadData(ObjectManager $manager): void
	{
		$admin = $manager->getRepository(Admin::class)->findOneBy(['email' => 'cinema-club@admin.info']);

		if (null === $admin) {
			$this->createEntity(Admin::class, 1, function (Admin $user) {
				$user
					->setEmail('cinema-club@admin.info')
					->setRoles(["ROLE_ADMIN"])
					->setPassword($this->passwordEncoder->hashPassword($user, 'super-password'));
			});

			$manager->flush();
		}
	}

//	public function getDependencies(): array
//	{
//		return [
//			BaseFixture::class,
//		];
//	}
}
