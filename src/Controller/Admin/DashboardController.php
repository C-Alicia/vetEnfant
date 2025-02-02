<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]   
    public function index(): Response
    {
        $url = $this->container->get(AdminUrlGenerator::class);
        
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirect($url->setController(UserCrudController::class)->generateUrl());
        } else {
            return $this->redirectToRoute('app_home');
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('VetEnfant');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour sur le site', 'fa-solid fa-arrow-left', 'app_home');
        yield MenuItem::section('Utilisateurs', 'fas fa-list');
        yield MenuItem::subMenu('Utilisateurs', 'fa-solid fa-user')->setSubItems([
                MenuItem::linkToCrud('Tous les Utilisateurs', 'fa fa-file-text', User::class)->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud('Ajouter un Utilisateur', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Catégories', 'fas fa-list');
        yield MenuItem::subMenu('Catégories', 'fa fa-tags')->setSubItems([
            MenuItem::linkToCrud('Toutes les catégories', 'fa fa-file-text', Category::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkToCrud('Ajouter une catégorie', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Produits', 'fas fa-list');
        yield MenuItem::subMenu('Produits', 'fa fa-tags')->setSubItems([
            MenuItem::linkToCrud('Toutes les produits', 'fa fa-file-text', Product::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkToCrud('Ajouter un produit', 'fas fa-plus', Product::class)->setAction(Crud::PAGE_NEW)
        ]);
    }
}
