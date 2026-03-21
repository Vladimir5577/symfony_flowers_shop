<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bambini Flowers')
            ->setFaviconPath('favicon.svg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Главная', 'fa fa-home');

        yield MenuItem::section('Каталог');
        yield MenuItem::linkTo(HomeProductCrudController::class, 'Главная страница', 'fa fa-star');
        yield MenuItem::linkTo(ProductCrudController::class, 'Товары', 'fa fa-leaf');
        yield MenuItem::linkTo(ProductImageCrudController::class, 'Фото товаров', 'fa fa-image');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Категории', 'fa fa-folder');
        yield MenuItem::linkTo(OccasionCrudController::class, 'Поводы', 'fa fa-heart');
        yield MenuItem::linkTo(BudgetTierCrudController::class, 'Бюджеты', 'fa fa-money-bill');

        yield MenuItem::section('Продажи');
        yield MenuItem::linkTo(OrderCrudController::class, 'Заказы', 'fa fa-shopping-cart');
        yield MenuItem::linkTo(PaymentCrudController::class, 'Платежи', 'fa fa-credit-card');

        yield MenuItem::section('Клиенты');
        yield MenuItem::linkTo(CustomerCrudController::class, 'Клиенты', 'fa fa-users');
    }
}
