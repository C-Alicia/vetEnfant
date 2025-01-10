<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupérer tous les nouveaux produits non vendus, triés par date de création
     *
     * @return Product[]
     */
    public function findAllNewProduct(): array
    {
        return $this->findBy(
            ['isSold' => false],  // Filtrer par isSold = 0 (non vendu)
            ['createdAt' => 'DESC'],         // Trier par date de création (du plus récent au plus ancien)
        );
    }

    /**
     * Récupérer les 9 premiers produits non vendus, triés par date de création
     *
     * @return Product[]
     */
    public function findAllProductActifLimit(): array
    {
        return $this->findBy(
            ['isSold' => false],  // Filtrer par isSold = 0 (non vendu)
            ['createdAt' => 'DESC'],         // Trier par date de création (du plus récent au plus ancien)
            9                                 // Limiter à 9 produits
        );
    }

    /**
     * Récupérer tous les produits non vendus uniquement les bébés 0 à 23 mois
     *
     * @return Product[]
     */
    public function findAllBabyProduct(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isSold = :isSold')  // Filtrer par isSold = false (non vendu)
            ->andWhere('p.size >= :minSize')  // Filtrer par taille minimale (0 mois)
            ->andWhere('p.size <= :maxSize')  // Filtrer par taille maximale (23 mois)
            ->setParameter('isSold', false)  // Paramètre isSold
            ->setParameter('minSize', 0)  // Paramètre taille minimale
            ->setParameter('maxSize', 23)  // Paramètre taille maximale
            ->orderBy('p.createdAt', 'DESC')  // Trier par date de création (du plus récent au plus ancien)
            ->getQuery()
            ->getResult();
    }
    /**
     * Récupérer tous les produits qui ont entre 36 et 192 mois, qui ne sont pas vendus, et uniquement gender (enum) girl et unisex
     *
     * @return Product[]
     */
    public function findAllGirlOrUnisexProduct(): array
    {
        return $this->createQueryBuilder('p')  // Assuming 'p' is the alias for Product
            ->where('p.isSold = :isSold')            // Filter for unsold products
            ->andWhere('p.size >= 36 AND p.size <= 192') // 3 years (36 months) to 16 years (192 months)
            ->andWhere('p.gender IN (:genders)')      // Filter for gender being 'girl' or 'unisex'
            ->setParameter('isSold', false)           // Bind 'isSold' to false (unsold products)
            ->setParameter('genders', ['girl', 'unisex']) // Bind 'genders' to 'girl' and 'unisex'
            ->getQuery()
            ->getResult(); // This will return an array of Product objects
    }

    /**
     * Récupérer tous les produits qui ont entre 36 et 192 mois, qui ne sont pas vendus, et uniquement gender (enum) girl et unisex
     *
     * @return Product[]
     */
    public function findAllBoyOrUnisexProduct(): array
    {
        return $this->createQueryBuilder('p')  // Assuming 'p' is the alias for Product
            ->where('p.isSold = :isSold')            // Filter for unsold products
            ->andWhere('p.size >= 36 AND p.size <= 192') // 3 years (36 months) to 16 years (192 months)
            ->andWhere('p.gender IN (:genders)')      // Filter for gender being 'girl' or 'unisex'
            ->setParameter('isSold', false)           // Bind 'isSold' to false (unsold products)
            ->setParameter('genders', ['boy', 'unisex']) // Bind 'genders' to 'boy' and 'unisex'
            ->getQuery()
            ->getResult(); // This will return an array of Product objects
    }
}