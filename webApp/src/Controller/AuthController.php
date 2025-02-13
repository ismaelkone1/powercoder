<?php
// src/Controller/AuthController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
    
        if ($request->isMethod('POST')) {
            // On récupère les données du formulaire
            $data = $request->request->all();
    
            $user->setEMail($data['email']);
            $user->setNom($data['nom']);
            $user->setRoles(['ROLE_USER']); // Role par défaut
            
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
    
            // On persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirection vers la page de login après l'inscription
            return $this->redirectToRoute('login');
        }
    
        // Si la méthode est GET, on retourne le formulaire d'inscription
        return $this->render('security/register.twig');
    }
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    
    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        // This method can be empty - it will be intercepted by the logout key on your firewall
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/', name: 'index')]
    
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

}