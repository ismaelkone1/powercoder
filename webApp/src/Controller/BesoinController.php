<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;
use App\Form\BesoinType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class BesoinController extends AbstractController
{
    #[Route('/besoins', name: 'liste_besoin', methods: ['GET'])]
    public function listeBesoins(): Response
    {

        return $this->render('besoin/liste_besoin.html.twig', [
            'controller_name' => 'BesoinController',
        ]);
    }

    #[Route('/besoin/create', name: 'create_besoin', methods: ['GET'])]
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
