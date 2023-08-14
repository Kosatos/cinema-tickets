<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Администраторы')
            ->setEntityLabelInSingular(fn(?Admin $admin) => $admin?->getEmail() ?: 'администратора')
            ->setPageTitle(CRUD::PAGE_EDIT, fn(?Admin $admin) => "Редактировать администратора: {$admin?->getEmail()}")
            ->setPageTitle(CRUD::PAGE_NEW, "Создать администратора")
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email', 'E-mail')
                ->setCustomOptions([
                    'error_bubbling' => false
                ])
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            ChoiceField::new('roles', 'Роли')
                ->setChoices(Admin::getAvailableRoles())
                ->allowMultipleChoices()
                ->setPermission('ROLE_ADMIN')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            TextField::new('password', 'HASH пароля')
                ->onlyOnForms()
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Используйте CLI для генерации пароля: <strong style="white-space: nowrap; color: #7c2d12">symfony console security:hash-password</strong>')
            ,
        ];
    }
}
