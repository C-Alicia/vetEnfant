<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Récupère tous les utilisateurs avec leurs relations.
     *
     * @return array
     */
    public function getAllUsers(): array
    {
        return array_map(fn(User $user) => $this->formatUser($user), $this->userRepository->findAll());
    }

    /**
     * Récupère un utilisateur par son ID avec ses relations.
     *
     * @param int $id
     * @return array|null
     */
    public function getUserById(int $id): ?array
    {
        $user = $this->userRepository->find($id);
        return $user ? $this->formatUser($user) : null;
    }

    /**
     * Crée un nouvel utilisateur.
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data): array
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setDateOfBirth(new \DateTime($data['dateOfBirth']));
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setDescription($data['description'] ?? null);
        $user->setProfileImage($data['profileImage'] ?? null);
        $user->setEvaluation($data['evaluation'] ?? null);
        $user->setCreatedAt(new \DateTime());
        $user->setRoles($data['roles'] ?? ['ROLE_USER']);
        $user->setIsActive($data['isActive'] ?? true);
        $user->setIsRole($data['isRole'] ?? false);

        // Encodage du mot de passe
        $encodedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $this->userRepository->save($user);
        return $this->formatUser($user);
    }

    /**
     * Modifier un utilisateur par son ID avec ses relations.
     *
     * @param int $id
     * @return bool|null
     * @param array $data
     */
    public function updateUser(int $id, array $data): ?array
    {
        // Récupérer l'utilisateur via l'ID
        $user = $this->userRepository->find($id);

        // Si l'utilisateur n'existe pas, retourner false
        if (!$user) {
            return null;
        }

        // Mettre à jour les informations de l'utilisateur
        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setFirstName($data['firstName'] ?? $user->getFirstName());
        $user->setLastName($data['lastName'] ?? $user->getLastName());
        $user->setDateOfBirth(isset($data['dateOfBirth']) ? new \DateTime($data['dateOfBirth']) : $user->getDateOfBirth());
        $user->setPhoneNumber($data['phoneNumber'] ?? $user->getPhoneNumber());
        $user->setDescription($data['description'] ?? $user->getDescription());
        $user->setProfileImage($data['profileImage'] ?? $user->getProfileImage());
        $user->setEvaluation($data['evaluation'] ?? $user->getEvaluation());
        $user->setIsActive($data['isActive'] ?? $user->isActive());
        $user->setIsRole($data['isRole'] ?? $user->isRole());

        // Si un nouveau mot de passe est fourni, le mettre à jour
        if (isset($data['password']) && !empty($data['password'])) {
            $encodedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($encodedPassword);
        }

        // Enregistrer les modifications
        $this->userRepository->update($user);
        return $this->formatUser($user);
    }

    /**
     * Supprimer un utilisateur par son ID avec ses relations.
     *
     * @param int $id
     * @return bool|null
     */

    public function deleteUser(int $id): ?array
    {
        // Récupérer l'utilisateur via l'ID
        $user = $this->userRepository->find($id);

        // Si l'utilisateur n'existe pas, retourner false
        /* if (!$user) {
            return false;
        } */
        // Supprimer l'utilisateur de la base de données
        $this->userRepository->remove($user);


        // Retourner true pour indiquer que la suppression a réussi
        return $user ? $this->formatUser($user) : null;
    }

    /**
     * Formate les données d'un utilisateur pour l'API.
     *
     * @param User $user
     * @return array
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'dateOfBirth' => $user->getDateOfBirth()?->format('Y-m-d'),
            'phoneNumber' => $user->getPhoneNumber(),
            'email' => $user->getEmail(),
            'description' => $user->getDescription(),
            'profileImage' => $user->getProfileImage(),
            'evaluation' => $user->getEvaluation(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            'roles' => $user->getRoles(),
            'isActive' => $user->isActive(),
            'isRole' => $user->isRole(),
            'address' => $this->formatAddresses($user),
            'orders' => $this->formatOrders($user),
            'products' => $this->formatProducts($user),
        ];
    }

    /**
     * Récupère les adresses d'un utilisateur.
     *
     * @param User $user
     * @return array
     */
    private function formatAddresses(User $user): array
    {
        return array_map(fn($address) => [
            'id' => $address->getId(),
            'street' => $address->getStreet(),
            'streetDelivery' => $address->getStreetDelivery(),
            'city' => $address->getCity(),
            'postalCode' => $address->getPostalCode()
        ], $user->getAddress()->toArray());
    }

    /**
     * Récupère les commandes d'un utilisateur.
     *
     * @param User $user
     * @return array
     */
    private function formatOrders(User $user): array
    {
        return array_map(fn($order) => [
            'id' => $order->getId(),
            'DateOfPurchase' => $order->getDateOfPurchase()?->format('Y-m-d'),
            'name' => $order->getName(),
            'comment' => $order->getComment(),
            'reference' => $order->getReference()
        ], $user->getOrderOrd()->toArray());
    }

    /**
     * Récupère les produits d'un utilisateur.
     *
     * @param User $user
     * @return array
     */
    private function formatProducts(User $user): array
    {
        return array_map(fn($product) => [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'slug' => $product->getSlug(),
            'price' => $product->getPrice(),

        ], $user->getProduct()->toArray());
    }
}
