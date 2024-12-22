<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public const USERS = [
        [
            'email' => 'jdoe@example.com',
            'roles' => ['ROLE_USER'],
            'password' => 'password123',
            'username' => 'jdoe',
            'lastname' => 'Doe',
            'firstname' => 'John',
            'dateOfBirth' => '1990-01-01',
            'phoneNumber' => '0623456789',
            'description' => 'Je suis John Doe.',
            'profileImage' => 'https://via.placeholder.com/100',
            'evaluation' => 4.5,
            'createdAt' => '2023-01-01 12:00:00',
            'isActive' => true,
        ],
        [
            'email' => 'asmith@example.com',
            'roles' => ['ROLE_USER'],
            'password' => 'securePass456',
            'username' => 'asmith',
            'lastname' => 'Smith',
            'firstname' => 'Alicia',
            'dateOfBirth' => '1985-05-20',
            'phoneNumber' => '0787654321',
            'description' => 'Je suis Alicia Smith.',
            'profileImage' => 'https://via.placeholder.com/100',
            'evaluation' => 4.8,
            'createdAt' => '2023-02-15 14:00:00',
            'isActive' => true,
        ],
        [
            'email' => 'bjohnson@example.com',
            'roles' => ['ROLE_USER'],
            'password' => 'anotherPassword789',
            'username' => 'bjohnson',
            'lastname' => 'Johnson',
            'firstname' => 'Bob',
            'dateOfBirth' => '1995-10-10',
            'phoneNumber' => '0655123456',
            'description' => 'Je suis Bob Johnson.',
            'profileImage' => 'https://via.placeholder.com/100',
            'evaluation' => 3.2,
            'createdAt' => '2023-03-01 10:30:00',
            'isActive' => false,
        ],
    ];
    
    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setPassword(password_hash($userData['password'], PASSWORD_BCRYPT));
            $user->setUsername($userData['username']);
            $user->setLastname($userData['lastname']);
            $user->setFirstname($userData['firstname']);
            $user->setDateOfBirth(new \DateTime($userData['dateOfBirth']));
            $user->setPhoneNumber($userData['phoneNumber']);
            $user->setDescription($userData['description']);
            $user->setProfileImage($userData['profileImage']);
            $user->setEvaluation($userData['evaluation']);
            $user->setCreatedAt(new \DateTime($userData['createdAt']));
            $user->setIsActive($userData['isActive']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
