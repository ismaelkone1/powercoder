<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BesoinController extends AbstractController
{
    #[Route('/besoins', name: 'liste_besoin', methods: ['GET'])]
    public function listeBesoins(): Response
    {

        return $this->render('besoin/liste_besoin.html.twig', [
            'controller_name' => 'BesoinController',
        ]);
    }
}
