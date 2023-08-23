<?php

namespace App\Controller\Admin\Trait;


use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

trait RepositoryTrait
{
	public function getRepository($object)
	{
	return $object->container->get(EntityRepository::class);
	}
}