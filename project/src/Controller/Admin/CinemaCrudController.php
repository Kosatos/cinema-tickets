<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\MediaGallery;
use App\Form\Admin\MediaGalleryType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use IntlDateFormatter;
use function Symfony\Component\String\s;

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
            ->setDefaultSort(['id' => 'ASC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Основное'),
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
                ->setHelp('Редактируемое поле. Необходимо для формирования корректных ссылок.')
            ,
            FormField::addRow(),
            TextareaField::new('description', 'Описание')
                ->setFormType(CKEditorType::class)
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
            ,
            FormField::addRow(),
            TextField::new('playbackTime', 'Продолжительность')
                ->formatValue(fn($value) => date('h:i', strtotime($value)))
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setHelp('Введите время проигрывания в формате <strong style="color: #7c2d12">HH:MM:SS</strong>')
            ,
            FormField::addRow(),
            AssociationField::new('countries', 'Страны')
                ->autocomplete()
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setTemplatePath('admin/crud/assoc_relations.html.twig')
            ,
            FormField::addTab('Галерея'),
            CollectionField::new('gallery', 'картинки')
                ->setTextAlign('center')
                ->setColumns('col-sm-6 col-lg-5 col-xxl-3')
                ->setEntryType(MediaGalleryType::class)
                ->setFormTypeOptions([
                    'error_bubbling' => false,
                ])
                ->renderExpanded()
                ->setTemplatePath('admin/crud/assoc_gallery.html.twig')
        ];
    }
}
