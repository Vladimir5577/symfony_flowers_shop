<?php

namespace App\Controller\Admin;

use App\Entity\BudgetTier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BudgetTierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BudgetTier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Бюджет')
            ->setEntityLabelInPlural('Бюджеты')
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('slug', 'Slug');
        yield TextField::new('label', 'Лейбл');
        yield TextField::new('description', 'Описание');
        yield IntegerField::new('minPrice', 'Мин. цена (₽)');
        yield IntegerField::new('maxPrice', 'Макс. цена (₽)');
        yield IntegerField::new('sortOrder', 'Порядок');
    }
}
