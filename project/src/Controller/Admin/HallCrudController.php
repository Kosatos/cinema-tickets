<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use App\Entity\Session;
use DateTimeImmutable;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class HallCrudController extends AbstractCrudController
{
    public function __construct(private readonly ManagerRegistry   $managerRegistry,
                                private readonly AdminUrlGenerator $urlGenerator,
                                private readonly RequestStack      $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Hall::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Залы')
            ->setEntityLabelInSingular('Зал')
            ->setDefaultSort(['number' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $addMainSession = Action::new('add session', 'добавить сеансы')
            ->displayAsLink()
            ->linkToCrudAction('addMainSession');

        $showMainSessions = Action::new('show main sessions', 'показать сеансы')
            ->displayIf(fn(?Hall $hall) => $hall->getSessions()->count() > 0)
            ->displayAsLink()
            ->linkToCrudAction('showMainSessions');

        return $actions
            ->add(Crud::PAGE_INDEX, $showMainSessions)
            ->add(Crud::PAGE_INDEX, $addMainSession);
    }

    public function showMainSessions(AdminContext $context): RedirectResponse
    {
        /**@var Hall $hall */
        $hall = $context->getEntity()->getInstance();

        $sessionsId = implode(',', $hall->getSessions()->map(fn(Session $session) => $session->getId())->toArray());

        $url = $this->urlGenerator->unsetAll()
            ->setController(SessionCrudController::class)
            ->setAction(Crud::PAGE_INDEX)
            ->set('entity_collection', $sessionsId)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function addMainSession(AdminContext $context): RedirectResponse
    {
        $session = new Session();
        $session->setData(new DateTimeImmutable());

        /**@var Hall $currentHall */
        $currentHall = $context->getEntity()->getInstance();
        $currentHall->addSession($session);

        $em = $this->managerRegistry->getManager();

        $em->persist($session);
        $em->flush();

        $url = $this->urlGenerator->unsetAll()
            ->setController(SessionCrudController::class)
            ->setEntityId($session->getId())
            ->setAction(Crud::PAGE_EDIT)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('number', 'Номер зала')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Укажите номер зала.')
            ,
//            FormField::addRow(),
//            CollectionField::new('sessions', 'Сеансы')
//                ->setTextAlign('center')
//                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
//                ->setEntryType(SessionParentType::class)
//                ->setFormTypeOptions([
//                    'by_reference' => false,
//                    'mapped' => true
//                ])
//                ->renderExpanded()
//                ->setTemplatePath('admin/crud/assoc_relations.html.twig')
//            ,
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $repository = $this->container->get(EntityRepository::class);

        if ($this->requestStack->getCurrentRequest()->query->has('entityId')) {
            $id = $this->requestStack->getCurrentRequest()->query->get('entityId');

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

        return $repository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
    }
}
