<?php

namespace App\Controller\Admin;

use App\Entity\Seat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;


class SeatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Seat::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Места')
            ->setEntityLabelInSingular('Место');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ArrayField::new('identifier', 'Ряд и Место')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Идентифицируйте место в формате: <p><strong style="color: #7c2d12">НОМЕР РЯДА, НОМЕР МЕСТА</strong></p>')
            ,
            BooleanField::new('isVip', 'VIP')
                ->onlyOnIndex()
            ,
            FormField::addRow(),
            AssociationField::new('hall', 'Зал')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Выберете номер зала, для которого формируется схема мест.')
        ];
    }
}
