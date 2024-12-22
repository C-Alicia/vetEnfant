<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\UserMessage;
use App\Entity\User;
use App\Entity\Message;

class UserMessageFixtures extends Fixture
{
    public const USERMESSAGES = [
        [
            'user_id' => 1, // ID de l'utilisateur John Doe
            'message_id' => 1, // ID du message 1
        ],
        [
            'user_id' => 2, // ID de l'utilisateur Alicia Smith
            'message_id' => 2, // ID du message 2
        ],
        [
            'user_id' => 3, // ID de l'utilisateur Bob Johnson
            'message_id' => 3, // ID du message 3
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERMESSAGES as $userMessageData) {
            $userMessage = new UserMessage();
            
            // Récupérer l'utilisateur et le message en fonction de leurs ID
            $user = $manager->getRepository(User::class)->find($userMessageData['user_id']);
            $message = $manager->getRepository(Message::class)->find($userMessageData['message_id']);
            
            if ($user && $message) {
                // Associer l'utilisateur et le message à l'entité UserMessage
                $userMessage->setUser($user);
                $userMessage->setMessage($message);
                
                // Persister l'entité UserMessage
                $manager->persist($userMessage);
            }
        }

        // Enregistrer les entités dans la base de données
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            MessageFixtures::class,
        ];
    }
}
