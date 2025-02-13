<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateAdminCommand extends Command
{
    // Définir le nom de la commande comme une constante de classe
    private const COMMAND_NAME = 'app:create-admin';
    
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct(self::COMMAND_NAME); // Passer le nom de la commande au constructeur parent
        
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crée un nouvel administrateur')
            ->addArgument('nom', InputArgument::REQUIRED, 'Le nom de l\'administrateur')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email de l\'administrateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Le mot de passe de l\'administrateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $nom = $input->getArgument('nom');

        // Vérifier si l'utilisateur existe déjà
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $email])) {
            $io->error(sprintf('Un utilisateur avec l\'email %s existe déjà', $email));
            return Command::FAILURE;
        }

        try {
            $user = new User();
            $user->setEmail($email);
            $user->setNom($nom);
            $user->setRoles(['ROLE_ADMIN']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success([
                'Administrateur créé avec succès !',
                sprintf('Nom : %s', $nom),
                sprintf('Email : %s', $email)
            ]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Une erreur est survenue lors de la création de l\'administrateur : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}