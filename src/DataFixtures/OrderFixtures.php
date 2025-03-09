<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\OrderOrd;
use App\Entity\User;

class OrderFixtures extends Fixture
{
    public const ORDERS = [
        [
            'name' => 'Commande #1',
            'dateOfPurchase' => '2023-12-01 14:30:00',
            'comment' => 'Première commande pour tester le système.',
            'createdAt' => '2023-12-01 12:00:00',
            'user_id' => 2, // ID de l'utilisateur */
            'reference' => 'COM0001'
        ],
        [
            'name' => 'Commande #2',
            'dateOfPurchase' => '2023-12-02 16:00:00',
            'comment' => 'Client très satisfait. Livraison rapide.',
            'createdAt' => '2023-12-02 13:30:00',
            'user_id' => 3, // ID de l'utilisateur */
            'reference' => 'COM0002'
        ],
        [
            'name' => 'Commande #3',
            'dateOfPurchase' => '2023-12-03 10:15:00',
            'comment' => 'Commande urgente pour un événement.',
            'createdAt' => '2023-12-03 08:00:00',
            'user_id' => 1, // ID de l'utilisateur */
            'reference' => 'COM0003'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ORDERS as $orderData) {
            $order = new OrderOrd();
            $order->setName($orderData['name']);
            $order->setDateOfPurchase(new \DateTime($orderData['dateOfPurchase']));
            $order->setComment($orderData['comment']);
            $order->setCreatedAt(new \DateTime($orderData['createdAt']));
            $order->setReference($orderData['reference']);

            // Associe l'utilisateur via l'ID (user_id)
            $user = $manager->getRepository(User::class)->find($orderData['user_id']);
            if ($user) {
                $order->setUser($user);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }
}
