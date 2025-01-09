<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ProductRepository;

#[Route('product', name: 'product_')]
class ProductController extends AbstractController
{
  #[Route('/', name: 'app_product')]
  public function index(): Response
  {
    return $this->render('product/index.html.twig');
  }

  #[Route('/{id}', name: 'details')]
  public function details(ManagerRegistry $doctrine, $id): Response
  {
    $product = $doctrine->getRepository(Product::class)->find($id);

    if (!$product) {
      throw new NotFoundHttpException("Product not found.");
    }

    return $this->render('product/details.html.twig', [
      'product' => $product
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
