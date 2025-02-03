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
<<<<<<< HEAD
=======
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ProductFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
>>>>>>> SellFonctionnality

#[Route('product', name: 'product_')]
class ProductController extends AbstractController
{
<<<<<<< HEAD

  #[Route('/newproduct', name: 'newProduct')]
=======
  private $productRepo;

  public function __construct(ProductRepository $productRepo)
  {
    $this->productRepo = $productRepo;
  }

  private function getProductsWithCount(ProductRepository $prodRepo, string $methodName): array
  {
    try {
      // Appeler la méthode dynamique du repository pour récupérer les produits
      $products = $prodRepo->{$methodName}();

      // Vérifier si des produits ont été trouvés
      if (!$products) {
        throw new NotFoundHttpException('Aucun produit trouvé.');
      }

      // Retourner les produits et le nombre
      return [
        'products' => $products,
        'productsCount' => count($products)
      ];
    } catch (NotFoundHttpException $e) {
      // Retourner un message d'erreur et un tableau vide
      return [
        'products' => [],
        'productsCount' => 0,
        'message' => $e->getMessage()
      ];
    }
  }

  #[Route('/new', name: 'productNew')]
>>>>>>> SellFonctionnality
  public function showAllNewProduct(ProductRepository $prodRepo): Response
  {
    $data = $this->getProductsWithCount($prodRepo, 'findAllNewProduct');

    return $this->render('product/productNew.html.twig', [
      'products' => $data['products'],
      'productsCount' => $data['productsCount'],
      'message' => $data['message'] ?? null
    ]);
  }

  #[Route('/baby', name: 'productBaby')]
  public function showAllBabyProduct(ProductRepository $prodRepo): Response
  {
    $data = $this->getProductsWithCount($prodRepo, 'findAllBabyProduct');

    return $this->render('product/productBaby.html.twig', [
      'products' => $data['products'],
      'productsCount' => $data['productsCount'],
      'message' => $data['message'] ?? null
    ]);
  }

  #[Route('/girl', name: 'productGirl')]
  public function showAllGirlsProduct(ProductRepository $prodRepo): Response
  {
    $data = $this->getProductsWithCount($prodRepo, 'findAllGirlOrUnisexProduct');

    return $this->render('product/productGirl.html.twig', [
      'products' => $data['products'],
      'productsCount' => $data['productsCount'],
      'message' => $data['message'] ?? null
    ]);
  }

  #[Route('/boy', name: 'productBoy')]
  public function showAllBoyProduct(ProductRepository $prodRepo): Response
  {
    $data = $this->getProductsWithCount($prodRepo, 'findAllBoyOrUnisexProduct');

    return $this->render('product/productBoy.html.twig', [
      'products' => $data['products'],
      'productsCount' => $data['productsCount'],
      'message' => $data['message'] ?? null
    ]);
  }

<<<<<<< HEAD




  #[Route('/{id<\d+>}', name: 'details')]
  public function details(ManagerRegistry $doctrine, $id): Response
=======
  #[Route('/details/{slug}', name: 'details')]
  public function details(ManagerRegistry $doctrine, string $slug): Response
>>>>>>> SellFonctionnality
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
  public function addToProduct(Request $request, ProductRepository $productRepo, SluggerInterface $slugger, PictureService $pictureService, TokenStorageInterface $tokenStorage, EntityManagerInterface $em): Response
  {
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
