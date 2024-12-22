<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;
use App\Entity\Product;

class ImageFixtures extends Fixture
{
    public const IMAGES = [
        [
            'name' => 'Robe rose',
            'src' => 'https://images.pexels.com/photos/5693889/pexels-photo-5693889.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'altText' => 'robe rose',
            'product_id' => 1, // ID du produit */
        ],
        [
            'name' => 'Ensemble haut et bas garçon',
            'src' => 'https://images.pexels.com/photos/5693888/pexels-photo-5693888.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'altText' => 'Ensemble pour garçon',
            'product_id' => 2, // ID du produit */
        ],
        [
            'name' => 'Costume père noël',
            'src' => 'https://images.pexels.com/photos/6437627/pexels-photo-6437627.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'altText' => 'Costume père noël',
            'product_id' => 3, // ID du produit */
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::IMAGES as $imageData) {
            $image = new Image();
            $image->setName($imageData['name']);
            $image->setSrc($imageData['src']);
            $image->setAltText($imageData['altText']);

            // Associer l'image à un produit par ID
            $product = $manager->getRepository(Product::class)->find($imageData['product_id']);
            if ($product) {
                $image->setProduct($product);
            }

            $manager->persist($image);
        }

        $manager->flush();
    }
}
