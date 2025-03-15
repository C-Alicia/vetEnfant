<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Claim;
use App\Entity\OrderOrd;

class ClaimFixtures extends Fixture
{
    public const CLAIMS = [
        [
            'name' => 'Réclamation #1',
            'description' => 'Produit endommagé lors de la livraison.',
            'createdAt' => '2023-12-01 14:30:00',
            'isStatus' => false,
            'order_ord_id' => 1,
        ],
        [
            'name' => 'Réclamation #2',
            'description' => 'Erreur dans la quantité commandée.',
            'createdAt' => '2023-12-02 16:00:00',
            'isStatus' => true,
            'order_ord_id' => 2,
        ],
        [
            'name' => 'Réclamation #3',
            'description' => 'Retard de livraison.',
            'createdAt' => '2023-12-03 10:15:00',
            'isStatus' => false,
            'order_ord_id' => 3,
        ], 
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CLAIMS as $claimData) {
            $claim = new Claim();
            $claim->setName($claimData['name']);
            $claim->setDescription($claimData['description']);
            $claim->setCreatedAt(new \DateTime($claimData['createdAt']));
            $claim->setStatus($claimData['isStatus']);

            // Associer la réclamation à une commande par ID
            $order = $manager->getRepository(OrderOrd::class)->find($claimData['order_ord_id']);
            if ($order) {
                $claim->setOrderOrd($order);
            }
            $manager->persist($claim);
        }

        $manager->flush();
    }
}
