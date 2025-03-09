<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Category;
use App\Enum\Gender;
use App\Entity\OrderOrd;
use App\Entity\User;
use App\Enum\State;

class ProductFixtures extends Fixture
{
    public const PRODUCTS = [
        [
            'name' => 'Doudoune rose et blanche',
            'gender' => Gender::GIRL,
            'state' => State::NEUFAVECETIQUETTE,
            'price' => 29.99,
            'size' => 4,
            'description' => 'Une veste confortable et impérmeable',
            'isSold' => false,
            'createdAt' => '2023-01-15',
            'category_id' => 8,
            'order_ord_id' => 1,
            'user_id' => 2,
            'slug' => 'doudoune_rose_et_blanche'
        ],
        [
            'name' => 'Ensemble Haut et bas',
            'gender' => Gender::BOY,
            'state' => State::BONETAT,
            'price' => 34.99,
            'size' => 1,
            'description' => 'Haut et pantalon résistant pour les garçons.',
            'isSold' => true,
            'createdAt' => '2023-02-10',
            'category_id' => 2,
            'user_id' => 3,
            'order_ord_id' => 2,
            'slug' => 'ensemble_haut_bas'
        ],
        [
            'name' => 'Pyjamas de motif père-Noël',
            'gender' => Gender::UNISEXE,
            'state' => State::TRESBONETAT,
            'price' => 19.99,
            'size' => 9,
            'description' => 'pyjamas motif de noël.',
            'isSold' => false,
            'createdAt' => '2023-03-01',
            'category_id' => 9,
            'user_id' => 3,
            'order_ord_id' => 3,
            'slug' => 'pyjamin_pere_noel'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setGender($productData['gender']);
            $product->setState($productData['state']);
            $product->setPrice($productData['price']);
            $product->setSize($productData['size']);
            $product->setDescription($productData['description']);
            $product->setIsSold($productData['isSold']);
            $product->setCreatedAt(new \DateTime($productData['createdAt']));
            $product->setSlug($productData['slug']);

             // Associe l'utilisateur via l'ID (user_id)
             $user = $manager->getRepository(User::class)->find($productData['user_id']);
             if ($user) {
                 $product->setUser($user);
             }
                         
            // Associe l'ID de la commande via l'order_id
            $order = $manager->getRepository(OrderOrd::class)->find($productData['order_ord_id']);
            if ($order) {
                $product->setOrderOrd($order);
            }

            $category = $manager->getRepository(CATEGORY::class)->find($productData['category_id']);
            if ($category) {
                $product->setCategory($category);
            }
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}