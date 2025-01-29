<?php

namespace App\Tests\Controller;

use App\Controller\ProductController;
use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PictureService;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class ProductControllerTest extends WebTestCase
{
    public function testCreateProductWithImages()
    {
        // Création du client pour envoyer des requêtes HTTP
        $client = static::createClient();

        // Mocking des services nécessaires
        $slugger = $this->createMock(SluggerInterface::class);
        $pictureService = $this->createMock(PictureService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $productRepo = $this->createMock(ProductRepository::class);

        // Créer un produit factice
        $product = new Product();
        $product->setName('Test Product');
        $images = ['image1.jpg', 'image2.jpg'];
        $folder = 'products';

        // Paramétrage des mocks
        $slugger->expects($this->once())
            ->method('slug')
            ->willReturn('test-product');

        $pictureService->expects($this->exactly(2))
            ->method('add')
            ->willReturnCallback(function ($image, $folder) {
                return 'path_to_' . $image;
            });

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($product);
        $entityManager->expects($this->once())
            ->method('flush');

        // Simuler la méthode createProductWithImages
        $productRepo->expects($this->once())
            ->method('createProductWithImages')
            ->with($product, $images, $folder, $slugger, $pictureService, $entityManager)
            ->willReturn($product);

        // Injecter les mocks dans le conteneur de services
        $container = self::getContainer();
        $container->set(ProductRepository::class, $productRepo);

        // Effectuer la requête HTTP pour tester l'action
        $client->request('POST', '/product/create', [
            'name' => 'Test Product',
            'images' => $images,
            'folder' => $folder
        ]);

        // Vérifier la réponse
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Product created successfully', $client->getResponse()->getContent());
    }
}
