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
      'addresses' => $this->formatAddresses($user),
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
      'city' => $address->getCity(),
      'country' => $address->getCountry()
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
      'orderDate' => $order->getOrderDate()?->format('Y-m-d')
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
      'price' => $product->getPrice()
    ], $user->getProduct()->toArray());
  }
}
