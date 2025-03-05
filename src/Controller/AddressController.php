<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

namespace App\Controller;

use App\Entity\Address;
use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/addresses')]
class AddressController extends AbstractController
{
    private AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

   /*  #[Route('/addresses', name: 'get_all_addresses', methods: ['GET'])]
    public function getAllAddresses(): JsonResponse
    {
        $addresses = $this->addressService->getAllAddresses();
        return new JsonResponse($addresses, Response::HTTP_OK);
    } */

    #[Route('/address/{id}', name: 'get_address_by_id', methods: ['GET'])]
    public function getAddressById(int $id): JsonResponse
    {
        // Appel de la méthode service pour récupérer l'adresse par ID
        $address = $this->addressService->getAddressById($id);
     
        // Vérifier si l'adresse a été trouvée
        if (!$address) {
            return new JsonResponse(['message' => 'Adresse non trouvée'], Response::HTTP_NOT_FOUND);
        }
    
        // Si l'adresse est trouvée, retourner la réponse avec les données
        return new JsonResponse($address, Response::HTTP_OK);
    }
    

    /* #[Route('/addresses/{Id}', name: 'get_addresses_by_user', methods: ['GET'])]
    public function getAddressesByUser(int $userId): JsonResponse
    {
        $addresses = $this->addressService->getAddressesByUser($userId);
        return new JsonResponse($addresses, Response::HTTP_OK);
    }

    #[Route('/address', name: 'create_address', methods: ['POST'])]
    public function createAddress(Request $request): JsonResponse
    {
        // Decode the incoming JSON data
        $data = json_decode($request->getContent(), true);

        // Define required fields
        $requiredFields = ['street', 'streetDelivery', 'city', 'postalCode', 'userId'];

        // Validate if all required fields are present
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse([
                    'message' => "Le champ '$field' est requis."
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            // Call the service to create the address with the userId
            $address = $this->addressService->createAddress($data['userId'], $data);

            // Return the created address with a 201 status code
            return new JsonResponse($address, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Erreur lors de la création de l’adresse.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/address/update/{id}', name: 'update_address', methods: ['PUT'])]
    public function updateAddress(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $updatedAddress = $this->addressService->updateAddress($id, $data);

            if (!$updatedAddress) {
                return new JsonResponse(['message' => 'Adresse non trouvée'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'message' => 'Adresse mise à jour avec succès',
                'address' => $updatedAddress
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Erreur lors de la mise à jour de l’adresse.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('address/delete/{id}', name: 'delete_address', methods: ['DELETE'])]
    public function deleteAddress(int $id): JsonResponse
    {
        try {
            $deleted = $this->addressService->deleteAddress($id);
            if (!$deleted) {
                return new JsonResponse(['message' => 'Adresse non trouvée'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse(['message' => 'Adresse supprimée avec succès'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Erreur lors de la suppression de l’adresse.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } */
}

