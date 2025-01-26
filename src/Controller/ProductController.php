<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ProductRepository;
use App\Enum\Gender;
use App\Enum\State;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ProductFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('product', name: 'product_')]
class ProductController extends AbstractController
{
  private $productRepo;

  public function __construct(ProductRepository $productRepo)
  {
    $this->productRepo = $productRepo;
  }

  #[Route('/new', name: 'productNew')]
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

  #[Route('/details/{slug}', name: 'details')]
  public function details(ManagerRegistry $doctrine, string $slug): Response
  {
      $product = $doctrine->getRepository(Product::class)->findOneBy(['slug' => $slug]);
  
      if (!$product) {
          throw new NotFoundHttpException("Produit introuvable.");
      }
  
      return $this->render('product/details.html.twig', [
          'product' => $product
      ]);
  }

  #[Route('/sell', name: 'productSell')]
  public function add(  Request $request, ProductRepository $productRepo, SluggerInterface $slugger, PictureService $pictureService, TokenStorageInterface $tokenStorage, EntityManagerInterface $em): Response {
  // Créer un nouveau produit
  $product = new Product();

  // Récupérer l'utilisateur connecté via le TokenStorage
  $user = $tokenStorage->getToken()?->getUser();

  if ($user instanceof User) {
    $product->setUser($user);
  }

  // Créer le formulaire
  $productForm = $this->createForm(ProductFormType::class, $product);
  $productForm->handleRequest($request);

  // Vérifier si le formulaire est soumis et valide
  if ($productForm->isSubmitted() && $productForm->isValid()) {
    // Récupérer les images
    $images = $productForm->get('image')->getData();

    // Utiliser le repository pour créer le produit avec ses images
    $productRepo->createProductWithImages($product, $images, 'products', $slugger, $pictureService, $em);

    // Afficher un message de succès
    $this->addFlash('success', 'Produit ajouté avec succès');

    // Redirection après ajout
    return $this->redirectToRoute('product_productNew');
  }

  return $this->render('product/productSell.html.twig', [
    'productForm' => $productForm->createView(),
  ]);
}
}
