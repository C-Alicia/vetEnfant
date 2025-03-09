<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'T-shirt',
        'Pantalons',
        'Robes',
        'Jupes',
        'Chapeaux',
        'Chaussures',
        'Vestes',
        'Pyjamas',
        'Accessoires',
        'Shorts',
        'Pulls',
        'Survetement'
    ];
    
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setDescription("Description pour la catégorie $categoryName");
            $category->setSlug($categoryName);

            $manager->persist($category);
            $this->addReference('category_' . strtolower(str_replace(' ', '_', $categoryName)), $category);
        }
        $manager->flush();
    }
}
