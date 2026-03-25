<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\OrderStatus;
use App\Enum\DeliveryType;
use App\Enum\PaymentMethod;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Заказ')
            ->setEntityLabelInPlural('Заказы')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('orderNumber', '№ заказа');
        yield TextField::new('recipientName', 'Получатель');
        yield TextField::new('recipientPhone', 'Телефон');
        yield ChoiceField::new('status', 'Статус')
            ->setChoices(array_combine(
                array_map(fn(OrderStatus $s) => $s->value, OrderStatus::cases()),
                OrderStatus::cases(),
            ));
        yield IntegerField::new('totalAmount', 'Сумма (₽)');
        yield ChoiceField::new('deliveryType', 'Доставка')
            ->setChoices(array_combine(
                array_map(fn(DeliveryType $d) => $d->value, DeliveryType::cases()),
                DeliveryType::cases(),
            ))->hideOnForm();
        yield TextField::new('deliveryAddress', 'Адрес')->hideOnIndex();
        yield TextField::new('deliveryTimeSlot', 'Время')->hideOnIndex();
        yield TextareaField::new('comment', 'Комментарий')->hideOnIndex();
        yield ChoiceField::new('paymentMethod', 'Оплата')
            ->setChoices(array_combine(
                array_map(fn(PaymentMethod $p) => $p->value, PaymentMethod::cases()),
                PaymentMethod::cases(),
            ))->hideOnIndex();
        yield AssociationField::new('customer', 'Клиент')->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Создан')->hideOnForm();
    }
}
