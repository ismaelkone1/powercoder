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
        $data = $request->request->all();

        if ($data['password'] !== $data['confirm_password']) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas');
            return $this->render('security/register.twig', [
                'error' => 'Les mots de passe ne correspondent pas',
                'last_email' => $data['email'],
                'last_nom' => $data['nom']
            ]);
        }

        // Vérification si l'email existe déjà
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            $this->addFlash('error', 'Cet email est déjà utilisé');
            return $this->render('security/register.twig', [
                'error' => 'Cet email est déjà utilisé',
                'last_nom' => $data['nom']
            ]);
        }

        $user->setEMail($data['email']);
        $user->setNom($data['nom']);
        $user->setRoles(['ROLE_USER']); // Role par défaut
        
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        try {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('login');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'inscription');
            return $this->render('security/register.twig', [
                'error' => 'Une erreur est survenue lors de l\'inscription',
                'last_email' => $data['email'],
                'last_nom' => $data['nom']
            ]);
        }
    }

    return $this->render('security/register.twig');
}
#[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \Exception('ne devrait pas etre atteint');
    }
}