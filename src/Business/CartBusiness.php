<?php

namespace App\Business;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
class CartBusiness
{
    private $session;
    private ProductRepository $productRepo;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepo)
    {
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
    }

    public function getCart(): array
    {
        return $this->session->get('panier', []);
    }

    public function addProductToCart(Product $product): void
    {
        $id = $product->getId();
        $panier = $this->getCart();

        if (!isset($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id];
        }

        $this->session->set('panier', $panier);
    }

    public function removeProductFromCart(Product $product): void
    {
        $id = $product->getId();
        $panier = $this->getCart();

        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function clearCart(): void
    {
        $this->session->remove('panier');
    }

    public function calculateCartTotal(): array
    {
        $data = [];
        $total = 0;

        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepo->find($id);

            if (!$product) {
                continue;
            }

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            $total += $product->getPrice() * $quantity;
        }

        return ['data' => $data, 'total' => $total];
    }
}
