<?php

namespace App\Controller\Admin;

use App\Entity\HomeProduct;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class HomeProductCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AdminContextProviderInterface $adminContextProvider,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return HomeProduct::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Товар на главной')
            ->setEntityLabelInPlural('Главная страница')
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('product', 'Товар')
            ->setRequired(true)
            ->setQueryBuilder(function (QueryBuilder $qb) use ($pageName): QueryBuilder {
                if (Crud::PAGE_NEW === $pageName) {
                    $qb->andWhere('NOT EXISTS (SELECT 1 FROM App\Entity\HomeProduct hp WHERE hp.product = entity)');

                    return $qb;
                }

                if (Crud::PAGE_EDIT === $pageName) {
                    $instance = $this->adminContextProvider->getContext()->getEntity()->getInstance();
                    if ($instance instanceof HomeProduct && null !== $instance->getId()) {
                        $qb->andWhere('NOT EXISTS (SELECT 1 FROM App\Entity\HomeProduct hp WHERE hp.product = entity AND hp.id <> :currentHomeProductId)')
                            ->setParameter('currentHomeProductId', $instance->getId());
                    }
                }

                return $qb;
            });
        yield IntegerField::new('sortOrder', 'Порядок');
    }
}
