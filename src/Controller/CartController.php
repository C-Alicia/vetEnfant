<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepo): Response
    {
        $panier = $session->get('panier', []);

        // on iniatialise des variables
        $data = [];
        $total = 0;

        /* $session->set('panier', []); */

        foreach ($panier as $id => $quantity) {
            $product = $productRepo->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit

        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);
        // on ajoute le produit dans le panier s'il n'y est pas encore
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id];
        }

        $session->set('panier', $panier);

        // on redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session): Response
    {
        // On récupère l'id du produit

        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // on retirer le produit du panier s'il n'y
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        // on redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    public function panierAction(Request $request)
    {
        // Suppose que tu utilises une session pour stocker les données du panier
        $panier = $request->getSession()->get('panier', []);

        // Calculer le nombre total d'articles dans le panier
        $totalItems = 0;
        foreach ($panier as $item) {
            $totalItems += $item['quantity'];
        }

        // Passer ce total à la vue
        return $this->render('cart/index.html.twig', [
            'total_items' => $totalItems,
            'data' => $panier,
        ]);
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}
