<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        /*Permet de définir l'interface d'acceuil lors de l'acces a la page admin */
        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Project'); //le nom associé au dashboard
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);//element du menu admin
        yield MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class);//element du menu admin
        yield MenuItem::linkToCrud('Categories', 'fas fa-list-ul', Category::class);//element du menu admin
        yield MenuItem::linkToCrud('Products', 'fas fa-tag', Product::class);//element du menu admin
        yield MenuItem::linkToCrud('Carriers', 'fas fa-truck', Carrier::class);//element du menu admin
        yield MenuItem::linkToCrud('Headers', 'fas fa-desktop', Header::class);//element du menu admin
    }
}
