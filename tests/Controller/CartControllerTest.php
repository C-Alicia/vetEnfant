<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndexWithEmptyCart()
    {
        // Tester un panier vide
        $this->client->request('GET', '/cart');
        
        // Récupérer la session via le client
        $session = $this->client->getContainer()->get('session');
        $session->set('panier', []);
        $session->save();

        $this->client->request('GET', '/cart');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Cart');
        $this->assertSelectorTextContains('.total', 'Total: 0');
    }

    public function testIndexWithProductsInCart()
    {
        // Panier avec des produits
        $this->client->request('GET', '/cart');
        
        // Récupérer la session et y ajouter un produit
        $session = $this->client->getContainer()->get('session');
        $session->set('panier', [1 => 2]); // Produit avec ID 1 et quantité 2
        $session->save();

        $this->client->request('GET', '/cart');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Cart');
        $this->assertSelectorTextContains('.total', 'Total:'); // Vérifie que le total est calculé
    }

    public function testAddProductToCart()
    {
        // Panier vide, ajout d'un produit
        $this->client->request('GET', '/cart/add/1');
        
        // Vérifier la session après ajout
        $session = $this->client->getContainer()->get('session');
        $panier = $session->get('panier');
        
        $this->assertArrayHasKey(1, $panier); // Produit ID 1 présent dans le panier
        $this->assertEquals(1, $panier[1]); // Quantité égale à 1
    }

    public function testDeleteProductFromCart()
    {
        // Initialiser le panier avec un produit
        $this->client->request('GET', '/cart/delete/1');
        
        // Vérifier la session après suppression
        $session = $this->client->getContainer()->get('session');
        $panier = $session->get('panier');
        
        $this->assertArrayNotHasKey(1, $panier); // Produit ID 1 ne doit plus être dans le panier
    }

    public function testAddProductIncreaseQuantity()
    {
        // Initialiser le panier avec un produit
        $this->client->request('GET', '/cart/add/1');
        
        // Vérifier la session après ajout
        $session = $this->client->getContainer()->get('session');
        $panier = $session->get('panier');
        
        $this->assertEquals(2, $panier[1]); // Le produit doit avoir une quantité de 2
    }
}
