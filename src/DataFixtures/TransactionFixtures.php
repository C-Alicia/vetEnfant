<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Transaction;
use App\Entity\OrderOrd;

class TransactionFixtures extends Fixture
{
    public const TRANSACTIONS = [
        [
            'name' => 'Transaction #1',
            'description' => 'Achat d’une combinaison pour fille',
            'createdAt' => '2023-12-01 10:00:00',
            'price' => 19.99,
            'acceptedDate' => '2023-12-01 10:30:00',
            'shippingDate' => '2023-12-01 14:00:00',
            'deliveredDate' => '2023-12-02 10:00:00',
            'discount' => 0.00,
            'paymentDate' => '2023-12-01 10:15:00',
            'cardNumber' => '4111111111111111',
            'order_ord_id' => 1, // ID de la commande */
            'reference' => 'TRN0001'
        ],
        [
            'name' => 'Transaction #2',
            'description' => 'Achat d’un jean pour garçon',
            'createdAt' => '2023-12-02 11:00:00',
            'price' => 50.00,
            'acceptedDate' => '2023-12-02 11:30:00',
            'shippingDate' => '2023-12-02 15:00:00',
            'deliveredDate' => '2023-12-03 12:00:00',
            'discount' => 0.00,
            'paymentDate' => '2023-12-02 11:20:00',
            'cardNumber' => '5500000000000004',
            'order_ord_id' => 2, // ID de la commande */
            'reference' => 'TRN0002'
        ],
        [
            'name' => 'Transaction #3',
            'description' => 'Achat d’un ensemble pour bébé',
            'createdAt' => '2023-12-03 09:00:00',
            'price' => 75.00,
            'acceptedDate' => '2023-12-03 09:15:00',
            'shippingDate' => '2023-12-03 10:00:00',
            'deliveredDate' => '2023-12-04 11:00:00',
            'discount' => 6.00,
            'paymentDate' => '2023-12-03 09:10:00',
            'cardNumber' => '340000000000009',
            'order_ord_id' => 3, // ID de la commande */
            'reference' => 'TRN0003'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TRANSACTIONS as $transactionData) {
            $transaction = new Transaction();
            $transaction->setName($transactionData['name']);
            $transaction->setDescription($transactionData['description']);
            $transaction->setCreatedAt(new \DateTime($transactionData['createdAt']));
            $transaction->setPrice($transactionData['price']);
            $transaction->setAcceptedDate(new \DateTime($transactionData['acceptedDate']));
            $transaction->setShippingDate(new \DateTime($transactionData['shippingDate']));
            $transaction->setDeliveredDate(new \DateTime($transactionData['deliveredDate']));
            $transaction->setDiscount($transactionData['discount']);
            $transaction->setPaymentDate(new \DateTime($transactionData['paymentDate']));
            $transaction->setCardNumber($transactionData['cardNumber']);
            $transaction->setReference($transactionData['reference']);

            // Associer la transaction à une commande existante
            $order = $manager->getRepository(OrderOrd::class)->find($transactionData['order_ord_id']);
            if ($order) {
                $transaction->setOrderOrd($order);
            }

            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
