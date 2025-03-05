<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function save(Address $address): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($address);
        $entityManager->flush();
    }
  
    public function update(Address $address): void
    {
        $entityManager = $this->getEntityManager();
        // Exécuter le flush pour appliquer les changements
        $entityManager->flush();
    }

    public function remove(Address $address): void
    {
        $entityManager = $this->getEntityManager();

        // Utiliser la méthode remove pour marquer l'entité pour suppression
        $entityManager->remove($address);

        // Exécuter le flush pour appliquer les changements
        $entityManager->flush();
    }
}
