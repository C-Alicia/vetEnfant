<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Message;

class MessageFixtures extends Fixture
{
    public const MESSAGES = [
        [
            'name' => 'Message de confirmation de commande',
            'content' => 'Votre commande a bien été enregistrée ! Vous recevrez une notification dès que vos articles seront expédiés. Merci pour votre achat !',
            'createdAt' => '2024-03-01 16:00:00',
        ],
        [
            'name' => 'Notification de disponibilité de produit',
            'content' => 'Bonne nouvelle ! Le produit que vous attendiez est désormais de nouveau en stock. Vous pouvez le commander dès maintenant avant qu\'il ne soit épuisé.',
            'createdAt' => '2024-04-01 10:00:00',
        ],
        [
            'name' => 'Réponse à une réclamation',
            'content' => 'Nous sommes désolés pour l\'inconvénient que vous avez rencontré avec votre commande. Nous avons pris en charge votre réclamation et nous vous contacterons bientôt pour résoudre le problème.',
            'createdAt' => '2024-05-01 11:00:00',
        ],
        [
            'name' => 'Message de retour',
            'content' => 'Nous avons bien reçu votre retour. L\'article sera échangé ou remboursé suivant votre choix. Merci pour votre patience.',
            'createdAt' => '2024-06-15 09:00:00',
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MESSAGES as $messageData) {
            $message = new Message();
            $message->setName($messageData['name']);
            $message->setContent($messageData['content']);
            $message->setCreatedAt(new \DateTime($messageData['createdAt']));
            
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getGroups(): array
    {
        return ['messages_group']; // Groupe pour cette fixture
    }
}