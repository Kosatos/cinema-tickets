<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use App\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Сеансы')
            ->setEntityLabelInSingular(fn(?Session $session) => $session?->getStartedAt() ?? 'Сеанс')
            ->setPageTitle(CRUD::PAGE_EDIT, fn(?Session $session) => "Редактировать сеанс: {$session?->getStartedAt()}")
            ->setPageTitle(CRUD::PAGE_NEW, "Сеанс")
            ->setDefaultSort(['started_at' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('started_at', 'Начало сеанса')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
        ];
    }
}
