<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Enum\ProductBadge;
use App\Enum\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Form\ProductImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Товар')
            ->setEntityLabelInPlural('Товары')
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield ImageField::new('mainPhoto', 'Фото')
            ->setBasePath('/uploads/products')
            ->onlyOnIndex();
        yield TextField::new('name', 'Название');
        yield TextField::new('fullName', 'Полное название')->hideOnIndex();
        yield TextField::new('slug', 'Slug')
            ->setHelp('Оставьте пустым — сгенерируется из названия')
            ->setRequired(false)
            ->hideOnIndex();
        yield TextareaField::new('description', 'Описание')->hideOnIndex();
        yield IntegerField::new('price', 'Цена (₽)');
        yield AssociationField::new('category', 'Категория');
        yield ChoiceField::new('type', 'Тип')
            ->setChoices(array_combine(
                array_map(fn(ProductType $t) => $t->value, ProductType::cases()),
                ProductType::cases(),
            ));
        yield ChoiceField::new('badge', 'Бейдж')
            ->setChoices(array_combine(
                array_map(fn(ProductBadge $b) => $b->value, ProductBadge::cases()),
                ProductBadge::cases(),
            ))
            ->setRequired(false);
        yield TextField::new('mood', 'Настроение')->hideOnIndex();
        yield BooleanField::new('inStock', 'В наличии');
        yield BooleanField::new('isGift', 'Подарок')->hideOnIndex();
        yield BooleanField::new('canBeCombined', 'Можно комбинировать')->hideOnIndex();
        yield AssociationField::new('occasions', 'Поводы')->hideOnIndex();
        yield AssociationField::new('budgetTiers', 'Бюджеты')->hideOnIndex();
        yield CollectionField::new('images', 'Фото')
            ->setEntryType(ProductImageType::class)
            ->setEntryIsComplex(true)
            ->allowAdd()
            ->allowDelete()
            ->hideOnIndex();
        yield IntegerField::new('sortOrder', 'Порядок')->hideOnIndex();
    }
}
