<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Image;

class ProductController extends AbstractController
{
     #[Route('/product', name: 'app_product')]
    public function index(): Response
    {    
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

  // Méthode pour afficher la liste des produits
   /*  // Récupération de tous les produits depuis la base de données
    $products = $doctrine->getRepository(Product::class)->findAll();

    if (!$products) {
      throw $this->createNotFoundException('Produit non trouvé');
    }

    // Rendu du template avec la liste des produits
    return $this->render('product/index.html.twig', [
      'products' => $products
    ]); */
  
}
