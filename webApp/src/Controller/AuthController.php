<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEMail($data['mail']);
        $user->setNom($data['nom']);
        $user->setRoles($data['role']);
        
        $hashedPassword = $passwordHasher->hashPassword($user, $data['mdp']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User registered successfully'
        ]);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(): Response
    {
        throw new \LogicException('This code should not be reached : Auth Failed');
    }
}