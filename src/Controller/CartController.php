<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Business\CartBusiness;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private CartBusiness $cartBusiness;

    public function __construct(CartBusiness $cartBusiness)
    {
        $this->cartBusiness = $cartBusiness;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $cartData = $this->cartBusiness->calculateCartTotal();

        return $this->render('cart/index.html.twig', [
            'data' => $cartData['data'],
            'total' => $cartData['total']
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product): Response
    {
        $this->cartBusiness->addProductToCart($product);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product): Response
    {
        $this->cartBusiness->removeProductFromCart($product);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(): Response
    {
        $this->cartBusiness->clearCart();

        return $this->redirectToRoute('cart_index');
    }
}

