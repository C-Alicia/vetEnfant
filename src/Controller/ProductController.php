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
use Symfony\Component\Security\Core\Security;

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

  #[Route('/sell', name: 'productSell')]
  public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService, Security $security): Response
  {
    /* $this->denyAccessUnlessGranted('ROLE_ADMIN'); */

    //On crée un "nouveau produit"
    $product = new Product();

    // Récupérer l'utilisateur actuellement connecté
    $user = $security->getUser();

    // Associer l'utilisateur au produit (si nécessaire)
    if ($user) {
      $product->setUser($user); // Assurez-vous que la méthode setUser() existe dans votre entité Product
    }

    // Associer l'utilisateur au produit (si nécessaire)
    $product->setUser($user); // Assurez-vous que la méthode `setUser` existe dans votre entité Product

    // On crée le formulaire
    $productForm = $this->createForm(ProductFormType::class, $product);

    // On traite la requête du formulaire
    $productForm->handleRequest($request);

    //On vérifie si le formulaire est soumis ET valide
    if ($productForm->isSubmitted() && $productForm->isValid()) {
      // On récupère les images
      $images = $productForm->get('image')->getData();
     /*  dd($images); */

      foreach ($images as $image) {
        // On définit le dossier de destination
        $folder = 'products';

        // On appelle le service d'ajout
        $fichier = $pictureService->add($image, $folder, 300, 300);

        $img = new Image();
        $img->setName($fichier);
        $product->addImage($img);
      }

      // On génère le slug
      $slug = $slugger->slug($product->getName());
      $product->setSlug($slug); 

      // On stocke
      $em->persist($product);
      $em->flush();

      $this->addFlash('success', 'Produit ajouté avec succès');

      // On redirige
      return $this->redirectToRoute('product_productNew');
    }

    return $this->render('product/productSell.html.twig', [
      'productForm' => $productForm->createView(),
    ]);
  }
}
