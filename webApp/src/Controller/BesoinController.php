<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;
use App\Form\BesoinType;
use Symfony\Component\HttpFoundation\Request;

final class BesoinController extends AbstractController
{
    #[Route('/besoins', name: 'liste_besoin')]
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

    #[Route('/besoins/{id}', name: 'liste_besoin_id')]
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

    #[Route('/besoin/create', name: 'create_besoin')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $besoin = new Besoin();
        $form = $this->createForm(BesoinType::class, $besoin);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($form->getData());
            // $entityManager->persist($besoin);
            // $entityManager->flush();

            return $this->redirectToRoute('create_besoin');
        }

        return $this->render('besoin/create_besoin.html.twig', [
            'controller_name' => 'BesoinController',
            'form' => $form,
        ]);
    }
}
