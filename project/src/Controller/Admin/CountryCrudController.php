<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\Country;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CountryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Country::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Страны')
            ->setEntityLabelInSingular(fn(?Country $country) => $country?->getName() ?: 'Страну')
            ->setPageTitle(CRUD::PAGE_EDIT, fn(?Country $country) => "Редактировать страну: {$country->getName()}")
            ->setPageTitle(CRUD::PAGE_NEW, "Страна")
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            SlugField::new('slug', 'Слаг')
                ->setTargetFieldName('name')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->onlyOnForms()
            ,
        ];
    }
}
