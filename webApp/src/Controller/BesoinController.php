<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;

final class BesoinController extends AbstractController
{
    #[Route('/besoins', name: 'liste_besoin', methods: ['GET'])]
    public function listeBesoins(EntityManagerInterface $emi): Response
    {
        try {
            $besoins = $emi->getRepository(Besoin::class)->findAll();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('besoin/liste_besoins.html.twig', [
            'controller_name' => 'BesoinController',
            'besoins' => $besoins
        ]);
    }

    #[Route('/besoin/{id}', name: 'liste_besoin_id', methods: ['GET'])]
    public function listeBesoinByClientId(EntityManagerInterface $emi, int $id): Response
    {
        try {
            $besoins = $emi->getRepository(Besoin::class)->findBy(['client_id' => $id]);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('besoin/liste_besoin_id.html.twig', [
            'controller_name' => 'BesoinController',
            'besoins' => $besoins
        ]);
    }
}
