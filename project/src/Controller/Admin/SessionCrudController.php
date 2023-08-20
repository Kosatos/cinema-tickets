<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    public function __construct(readonly private RequestStack      $requestStack,
                                readonly private AdminUrlGenerator $urlGenerator)
    {
    }

    public function configureActions(Actions $actions): Actions
    {
        $showHall = Action::new('comeback to hall', 'Зал')
            ->displayIf(fn(?Session $session) => $session->getHall())
            ->displayAsLink()
            ->linkToCrudAction('showHall');

        return $actions
            ->add(Crud::PAGE_INDEX, $showHall)
            ->remove('index', 'new');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Сеансы')
            ->setEntityLabelInSingular('Сеанс')
            ->setPageTitle(CRUD::PAGE_NEW, "Сеанс")
            ->setDefaultSort(['started_at' => 'ASC']);
    }

    public function showHall(AdminContext $context): RedirectResponse
    {
        /**@var Session $session */
        $session = $context->getEntity()->getInstance();

        $url = $this->urlGenerator->unsetAll()
            ->setController(HallCrudController::class)
            ->setEntityId($session->getHall()->getId())
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Session $session */
        $session = $this->getContext()->getEntity()->getInstance();

        if ($session && $session->getSession() == null) {

            return [
                DateTimeField::new('data', 'Дата 1')
                    ->setFormTypeOptions([
                        'input' => 'datetime_immutable',
                        'widget' => 'single_text',
                    ])
                    ->setTextAlign('center')
                    ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                    ->setHelp('Установите дату просмотра (день).')
                ,
                ChoiceField::new('schema', 'Схема сеансов 1')
                    ->setChoices(Session::getAvailavleSchemaName())
                    ->onlyOnForms()
            ];
        }

        if ($session?->getSession() != null) {

            return [
                TextField::new('started_at', 'Начало сеанса 2')
                    ->setTextAlign('center')
                    ->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            ];
        }

        return [
            DateTimeField::new('data', 'Дата')
                ->formatValue(function ($value, $entity) {
                    if (null == $value) {
                        $sessionId = $entity->getSession()->getId();

                        $url = $this->urlGenerator->unsetAll()->setController(SessionCrudController::class)
                            ->setAction(Crud::PAGE_INDEX)
                            ->set('entity_collection', $sessionId)
                            ->generateUrl();

                        return '<a href="' . $url . '">Вернуться к дате сеансов</a>';
                    }
                    return $entity;
                })
                ->setFormTypeOptions([
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                ])
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Установите дату просмотра (день).')
            ,
            TextField::new('started_at', 'Начало сеанса')
                ->formatValue(function ($value, $entity) {
                    if (null == $value) {
                        $idCollection = implode(',', $entity->getSessions()->map(fn(?Session $session) => $session->getId())->toArray());

                        $url = $this->urlGenerator->unsetAll()->setController(SessionCrudController::class)
                            ->setAction(Crud::PAGE_INDEX)
                            ->set('entity_collection', $idCollection)
                            ->generateUrl();

                        return '<a href="' . $url . '">Показать схему</a>';
                    }

                    return $entity;
                })
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
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
            ->andWhere('entity.id = :val')
            ->setParameter('val', null);
    }
}
