<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use Doctrine\ORM\QueryBuilder;
use App\Controller\Admin\Trait\RepositoryTrait;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCrudController extends AbstractCrudController
{
	use RepositoryTrait;
	public static function getEntityFqcn(): string
	{
		return Session::class;
	}

	public function __construct(readonly private RequestStack $requestStack)
	{
	}

	public function configureActions(Actions $actions): Actions
	{

		return $actions
			->remove('index', 'edit');
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setEntityLabelInPlural('Сеансы')
			->setEntityLabelInSingular('Сеанс')
			->setPageTitle(CRUD::PAGE_NEW, "Сеанс");
	}

	public function configureFields(string $pageName): iterable
	{
		return [
			DateTimeField::new('data', 'Дата')
				->setFormTypeOptions([
					'input' => 'datetime_immutable',
					'widget' => 'single_text',
				])
				->setTextAlign('center')
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->setHelp('Установите дату просмотра (день).')
			,
			FormField::addRow(),
			AssociationField::new('cinema', 'Фильм')
				->setTextAlign('center')
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->setHelp('Выберите фильм из выпадающего списка.')
			,
			FormField::addRow(),
			AssociationField::new('hall', 'Зал')
				->setTextAlign('center')
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->setHelp('Выберите фильм из выпадающего списка.')
			,
			FormField::addRow(),
			ChoiceField::new('schema', 'Схема сеансов')
				->setChoices(Session::getAvailavleSchemaName())
				->setColumns('col-sm-6 col-lg-5 col-xxl-3')
				->onlyOnForms()
				->setHelp('Выберете шаблон сеансов')
			,
		];
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
	{
		$repository = $this->container->get(EntityRepository::class);

		if ($this->requestStack->getCurrentRequest()->query->has('entity_id')) {
			$id = $this->requestStack->getCurrentRequest()->query->get('entity_id');

			return $repository->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
				->andWhere('entity.id = :val')
				->setParameter('val', $id);
		}

		if ($this->requestStack->getCurrentRequest()->query->has('entity_collection')) {
			$sessionsId = explode(',', $this->requestStack->getCurrentRequest()->query->get('entity_collection'));

			return $repository->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
				->andWhere('entity.id IN (:val)')
				->setParameter('val', $sessionsId);
		}

		return $repository->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
			->andWhere('entity.id > :val')
			->setParameter('val', 0);
	}
}
