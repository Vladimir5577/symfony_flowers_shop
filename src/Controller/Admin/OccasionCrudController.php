<?php

namespace App\Controller\Admin;

use App\Entity\Occasion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OccasionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Occasion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Повод')
            ->setEntityLabelInPlural('Поводы')
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('emoji', 'Эмодзи');
        yield TextField::new('name', 'Название');
        yield TextField::new('slug', 'Slug');
        yield IntegerField::new('sortOrder', 'Порядок');
    }
}
