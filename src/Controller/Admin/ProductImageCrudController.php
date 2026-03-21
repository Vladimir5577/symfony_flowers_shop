<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Фото товара')
            ->setEntityLabelInPlural('Фото товаров')
            ->setDefaultSort(['product' => 'ASC', 'sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('product', 'Товар');
        yield ImageField::new('imageName', 'Превью')
            ->setBasePath('/uploads/products')
            ->onlyOnIndex();
        yield ImageField::new('imageName', 'Текущее фото')
            ->setBasePath('/uploads/products')
            ->onlyOnDetail();
        yield TextField::new('imageFile', 'Загрузить фото')
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'allow_delete' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->onlyOnForms();
        yield IntegerField::new('sortOrder', 'Порядок');
    }
}
