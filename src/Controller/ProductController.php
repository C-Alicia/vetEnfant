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
use App\Enum\Gender;
use Symfony\Component\HttpFoundation\Request;

#[Route('product', name: 'product_')]
class ProductController extends AbstractController
{

  #[Route('/newproduct', name: 'newProduct')]
  public function showAllNewProduct(ProductRepository $prodRepo): Response
  {
    $products = [];
    $message = null;

    try {
      $products = $prodRepo->findAllNewProduct();

      // Vérifier si des produits ont été trouvés
      if (!$products) {
        throw new NotFoundHttpException('Aucun produit trouvé.');
      }
    } catch (NotFoundHttpException $e) {
      // Attraper l'exception et définir un message à transmettre à la vue
      $message = $e->getMessage(); // Récupérer le message de l'exception
    }

    // Calculer le nombre de produits
    $productsCount = count($products);


    return $this->render('product/productNew.html.twig', [
      'products' => $products,
      'productsCount' => $productsCount,
      'message' => $message
    ]);
  }

  // Méthode pour afficher les produits pour bébé
  #[Route('/baby', name: 'productBaby')]
  public function showAllBabyProduct(ProductRepository $prodRepo): Response
  {
    $products = [];
    $message = null;

    try {
      $products = $prodRepo->findAllBabyProduct();

      // Vérifier si des produits ont été trouvés
      if (!$products) {
        throw new NotFoundHttpException('Aucun produit trouvé.');
      }
    } catch (NotFoundHttpException $e) {
      // Attraper l'exception et définir un message à transmettre à la vue
      $message = $e->getMessage(); // Récupérer le message de l'exception
    }

    // Calculer le nombre de produits
    $productsCount = count($products);


    return $this->render('product/productBaby.html.twig', [
      'products' => $products,
      'productsCount' => $productsCount,
      'message' => $message
    ]);
  }

  // Méthode pour afficher les produits pour bébé
  #[Route('/girl', name: 'productGirl')]
  public function showAllGirlsProduct(ProductRepository $prodRepo): Response
  {
    $products = [];
    $message = null;

    try {
      $products = $prodRepo->findAllGirlOrUnisexProduct();

      // Vérifier si des produits ont été trouvés
      if (!$products) {
        throw new NotFoundHttpException('Aucun produit trouvé.');
      }
    } catch (NotFoundHttpException $e) {
      // Attraper l'exception et définir un message à transmettre à la vue
      $message = $e->getMessage(); // Récupérer le message de l'exception
    }

    // Calculer le nombre de produits
    $productsCount = count($products);


    return $this->render('product/productGirl.html.twig', [
      'products' => $products,
      'productsCount' => $productsCount,
      'message' => $message
    ]);
  }

  #[Route('/boy', name: 'productBoy')]
  public function showAllBoyProduct(ProductRepository $prodRepo): Response
  {
    $products = [];
    $message = null;

    try {
      $products = $prodRepo->findAllBoyOrUnisexProduct();

      // Vérifier si des produits ont été trouvés
      if (!$products) {
        throw new NotFoundHttpException('Aucun produit trouvé.');
      }
    } catch (NotFoundHttpException $e) {
      // Attraper l'exception et définir un message à transmettre à la vue
      $message = $e->getMessage(); // Récupérer le message de l'exception
    }

    // Calculer le nombre de produits
    $productsCount = count($products);


    return $this->render('product/productBoy.html.twig', [
      'products' => $products,
      'productsCount' => $productsCount,
      'message' => $message
    ]);
  }





  #[Route('/{id<\d+>}', name: 'details')]
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
}
