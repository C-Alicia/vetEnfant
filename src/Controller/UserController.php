 <?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route('/')]
class UserController extends AbstractController
{
  private UserService $userService;
  private ValidatorInterface $validator;

  public function __construct(UserService $userService, ValidatorInterface $validator)
  {
    $this->userService = $userService;
    $this->validator = $validator;
  }

  #[Route('', name: 'homepage', methods: ['GET'])]
  public function index(): Response
  {
    return new Response('<h1>Bienvenue sur Symfony !</h1>');
  }

  #[Route('users', name: 'get_users', methods: ['GET'])]
  public function getUsers(): JsonResponse
  {
    $users = $this->userService->getAllUsers();
    return new JsonResponse($users, Response::HTTP_OK);
  }

  #[Route('user/{id}', name: 'get_user_by_id', methods: ['GET'])]
  public function getUserById(int $id): JsonResponse
  {
    $user = $this->userService->getUserById($id);

    if (!$user) {
      return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
    }

    return new JsonResponse($user, Response::HTTP_OK);
  }

  #[Route('user', name: 'create_user', methods: ['POST'])]
  public function createUser(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    // Vérification des données requises
    $requiredFields = ['username', 'email', 'password', 'firstName', 'lastName', 'dateOfBirth'];
    foreach ($requiredFields as $field) {
      if (empty($data[$field])) {
        return new JsonResponse([
          'message' => "Le champ '$field' est requis."
        ], Response::HTTP_BAD_REQUEST);
      }
    }

    try {
      $user = $this->userService->createUser($data);
      return new JsonResponse($user, Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return new JsonResponse([
        'message' => 'Erreur lors de la création de l’utilisateur.',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }



}