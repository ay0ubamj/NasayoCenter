<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FormationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomFormation', 'Course title'),
            TextField::new('motivation', 'Small description')->onlyWhenCreating(),
            TextEditorField::new('description'),
            IdField::new('prixFormateur', 'Former price'),
            AssociationField::new('user', 'Students')->onlyWhenUpdating(),
            AssociationField::new('formateur', 'Former')->onlyWhenCreating(),
            ArrayField::new('whatuLearn', 'What students will learn')->onlyWhenCreating(),
            TextField::new('imageFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            DateTimeField::new('date', 'Course date')->setFormat('dd-MM-y HH:mm')->renderAsNativeWidget(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['date' => 'DESC'])
            ->setPageTitle('index', 'Administration - Courses');
    }
}
