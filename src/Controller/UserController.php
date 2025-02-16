<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Address;
use App\Entity\Product;
use App\Form\EditProfileType;
use App\Form\EditUtilisateurFormType;
use App\Form\EditUtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('profil', name: 'profil_')]
class UserController extends AbstractController
{
    #[Route('/seller', name: 'seller')]
    public function ShowSellerProfil(): Response
    {
        // Information à propos du vendeur verifier qu'il est bien vendeur
        $user = $this->getUser();

        // besoin de recuperer les produits créer par cette utilisateur


        return $this->render('user/profilSeller.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/user', name: 'user')]
    public function ShowProfile(EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est null
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion
        }

        // Récupérer la collection d'adresses
        $address = $user->getAddress();

        // Vérifier si la collection n'est pas vide
        if (!$address->isEmpty()) {
            // Récupérer la première adresse de la collection
            $address = $address->first();
        } else {
            $address = null; // Aucune adresse
        }

        // recuperer les produits

        // Récupérer les produits liés à cet utilisateur
        $products = $em->getRepository(Product::class)->findBy(['user' => $user]);

        // favoris et les mettre 

        // Passer les données à la vue
        return $this->render('user/profilUser.html.twig', [
            'user' => $user,
            'address' => $address, // Passer l'adresse à la vue
            'products' => $products
        ]);
    }

    #[Route('/edit', name: 'editProfil')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
    
        // Récupère la première adresse ou crée-en une nouvelle
        $address = $user->getAddress()->first() ?: new Address();
           
        if (!$user->getAddress()->contains($address)) {
            $user->addAddress($address);
        }
    
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
    
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('user_profil');
        }
    
        return $this->render('user/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit/password', name: 'editPassword')]
    public function editPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod("POST")) {
            $user = $this->getUser();

            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($hasher->hashPassword($user, $request->request->get('pass')));
                $em->persist($user);
                $em->flush();
                $this->addFlash('message', "Mot de passe à mis à jour avec succès");
                return $this->redirectToRoute('profil_user');
            } else {
                $this->addFlash('error', "Les deux mots de passe ne sont pas identiques !");
            }
        }

        return $this->render('user/editPassword.html.twig');
    }
}
