<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class AddressService
{
    private AddressRepository $addressRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AddressRepository $addressRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->addressRepository = $addressRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère toutes les adresses.
     *
     * @return array
     */
    public function getAllAddresses(): array
    {
        return array_map(fn(Address $address) => $this->formatAddress($address), $this->addressRepository->findAll());
    }

    /**
     * Récupère une adresse par son ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getAddressById(int $id): ?array
    {
        $address = $this->addressRepository->find($id);
        return $address ? $this->formatAddress($address) : null;
    }

    /**
     * Récupère les adresses d'un utilisateur spécifique.
     *
     * @param int $userId
     * @return array
     */
    public function getAddressesByUser(int $userId): array
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            return [];
        }

        $addresses = $this->addressRepository->findBy(['user' => $user]);
        return array_map(fn(Address $address) => $this->formatAddress($address), $addresses);
    }

    /**
     * Crée une nouvelle adresse pour un utilisateur.
     *
     * @param int $userId
     * @param array $data
     * @return array|null
     */
    public function createAddress(int $userId, array $data): ?array
    {
        // Find the user by userId
        $user = $this->userRepository->find($userId);

        // If user doesn't exist, return null
        if (!$user) {
            return null;
        }

        // Create a new address object
        $address = new Address();
        $address->setStreet($data['street']);
        $address->setStreetDelivery($data['streetDelivery'] ?? null);
        $address->setCity($data['city']);
        $address->setPostalCode($data['postalCode']);
        $address->setUser($user);

        // Persist and flush the address to the database
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        // Return the formatted address
        return $this->formatAddress($address);
    }


    /**
     * Met à jour une adresse.
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateAddress(int $id, array $data): ?array
    {
        $address = $this->addressRepository->find($id);
        if (!$address) {
            return null;
        }

        $address->setStreet($data['street'] ?? $address->getStreet());
        $address->setStreetDelivery($data['streetDelivery'] ?? $address->getStreetDelivery());
        $address->setCity($data['city'] ?? $address->getCity());
        $address->setPostalCode($data['postalCode'] ?? $address->getPostalCode());

        $this->entityManager->flush();

        return $this->formatAddress($address);
    }

    /**
     * Supprime une adresse.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAddress(int $id): bool
    {
        $address = $this->addressRepository->find($id);
        if (!$address) {
            return false;
        }

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Formate une adresse pour l'API.
     *
     * @param Address $address
     * @return array
     */
    private function formatAddress(Address $address): array
    {
        return [
            'id' => $address->getId(),
            'street' => $address->getStreet(),
            'streetDelivery' => $address->getStreetDelivery(),
            'city' => $address->getCity(),
            'postalCode' => $address->getPostalCode(),
            'userId' => $address->getUser()->getId(),
        ];
    }
}
