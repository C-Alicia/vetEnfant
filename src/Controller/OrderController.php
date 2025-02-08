<?php

namespace App\Controller;

use App\Entity\OrderOrd;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{

    #[Route('/add', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepo,  EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if ($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        //Le panier n'est pas vide, on crée la commande
        $order = new OrderOrd();

         // On remplit la commande
        $order->setUser($this->getUser());
        $order->setReference(uniqid());

        // On parcourt le panier pour créer les détails de commande
        foreach ($panier as $item => $quantity) {

            // On va chercher le produit
            $product = $productRepo->find($item);

            // 
            $price = $product->getPrice();
            


            dd($product);


            $order->addProduct($product);
        }
    }
}
