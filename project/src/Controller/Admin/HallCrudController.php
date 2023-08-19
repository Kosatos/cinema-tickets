<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use App\Form\Admin\SessionParentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class HallCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hall::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Залы')
            ->setEntityLabelInSingular(fn(?Hall $hall) => $hall?->getNumber() ?: 'Зал')
            ->setPageTitle(CRUD::PAGE_EDIT, fn(?Hall $hall) => "Редактировать зал номер: {$hall->getNumber()}")
            ->setPageTitle(CRUD::PAGE_NEW, "Зал")
            ->setDefaultSort(['number' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('number', 'Номер зала')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            CollectionField::new('sessions', 'Сеансы')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setEntryType(SessionParentType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'mapped' => true
                ])
                ->renderExpanded()
                ->setTemplatePath('admin/crud/assoc_relations.html.twig')
            ,
        ];
    }
}
