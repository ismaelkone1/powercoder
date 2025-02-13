<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionSalariesController extends AbstractController
{
    #[Route('/admin/salaries', name: 'app_gestion_salaries')]
    public function index(): Response
    {
        return $this->render('gestion_salaries/index.html.twig', [
            'controller_name' => 'GestionSalariesController',
        ]);
    }
}
