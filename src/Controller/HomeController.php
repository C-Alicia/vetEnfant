<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Image;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends AbstractController
{
    // Route d'accueil qui affiche la liste des produits
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $prodRepo): Response
    {
        $products = null;
        $message = null;

        try {
            // Récupérer les 9 premiers produits non vendus et nouveaux
            $products = $prodRepo->findAllProductActifLimit();

            // Vérifier si des produits ont été trouvés
            if (!$products) {
                throw new NotFoundHttpException('Aucun produit trouvé.');
            }
        } catch (NotFoundHttpException $e) {
            // Attraper l'exception et définir un message à transmettre à la vue
            $message = $e->getMessage(); // Récupérer le message de l'exception
        }

        // Obtenez l'utilisateur actuel
        $user = $this->getUser();

        // Afficher la page d'accueil pour tous les utilisateurs, connectés ou non
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'user' => $user,  // Vous pouvez afficher des informations personnalisées si l'utilisateur est connecté
            'message' => $message, // Passer le message à afficher dans Twig
        ]);
    }

}
