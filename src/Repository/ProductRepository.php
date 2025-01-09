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



    



    






    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
