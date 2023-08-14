<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use IntlDateFormatter;

class CinemaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cinema::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Фильмы')
            ->setEntityLabelInSingular(fn(?Cinema $cinema) => $cinema?->getName() ?: 'Фильм')
            ->setPageTitle(CRUD::PAGE_EDIT, fn(?Cinema $cinema) => "Редактировать фильм: {$cinema->getName()}")
            ->setPageTitle(CRUD::PAGE_NEW, "Фильм")
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название фильма')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            SlugField::new('slug', 'Слаг')
                ->setTargetFieldName('name')
                ->setTextAlign('center')
                ->onlyOnForms()
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            TextField::new('playbackTime', 'Длительность сеанса')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Введите время проигрывания в формате <strong style="color: #7c2d12">HH:MM:SS</strong>')
            ,
        ];
    }
}
