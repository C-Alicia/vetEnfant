<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Image;

class HomeController extends AbstractController
{
    // Route d'accueil qui affiche la liste des produits
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Récupérer les 9 premiers produits triés par createdAt
        $products = $doctrine->getRepository(Product::class)
            ->findBy([], ['createdAt' => 'DESC'], 9); // Limiter à 9 produits

        // Récupérer tous les produits pour l'option "Tout voir"
        $allProducts = $doctrine->getRepository(Product::class)
            ->findBy([], ['createdAt' => 'DESC']); // Tri par createdAt, tous les produits

        if (!$products) {
            throw $this->createNotFoundException('Aucun produit trouvé');
        }

        // Obtenez l'utilisateur actuel
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est connecté
        if ($user && $user->getUserIdentifier()) {
            // Rediriger ou afficher une vue spécifique si l'utilisateur est authentifié
            return $this->render('home/index.html.twig', [
                'products' => $products,
                'user' => $user,
                'allProducts' => $allProducts, // Passer tous les produits pour "Tout voir"
            ]);
        }

          // Redirection vers la page de connexion pour les utilisateurs non authentifiés
          return $this->redirectToRoute('app_login', [
            'redirect' => 'app_home', // Paramètre d'URL pour revenir à cette page après connexion
        ]);
    }
}