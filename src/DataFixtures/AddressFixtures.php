<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Address;
use App\Entity\User;

class AddressFixtures extends Fixture
{
    public const ADDRESSES = [
        [
            'street' => '12 rue des Lilas',
            'streetDelivery' => '15 rue des Fleurs',
            'postalCode' => '75001',
            'city' => 'Paris',
            'user_id' => 1  // ID de l'utilisateur John Doe
        ],
        [
            'street' => '25 avenue des Champs',
            'streetDelivery' => '3 boulevard Haussmann',
            'postalCode' => '69001',
            'city' => 'Lyon',
            'user_id' => 2 // ID de l'utilisateur Alicia Smith
        ],
        [
            'street' => '89 boulevard Victor Hugo',
            'streetDelivery' => '45 rue Voltaire',
            'postalCode' => '13001',
            'city' => 'Marseille',
            'user_id' => 3,  // ID de l'utilisateur Bob Johnson
        ],
    ];


    public function load(ObjectManager $manager): void
    {
        foreach (self::ADDRESSES as $addressData) {
            $address = new Address();
            $address->setStreet($addressData['street']);
            $address->setStreetDelivery($addressData['streetDelivery']);
            $address->setPostalCode($addressData['postalCode']);
            $address->setCity($addressData['city']);

            // Associer l'adresse à un utilisateur par ID
            $user = $manager->getRepository(User::class)->find($addressData['user_id']);
            if ($user) {
                // Si une relation entre User et Address existe, ajoutez ici la logique pour lier l'utilisateur
                $address->setUser($user);
            }
            $manager->persist($address);
        }

        $manager->flush();
    }
}
