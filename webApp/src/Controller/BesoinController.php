<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;
use App\Entity\User;
use App\Form\BesoinType;
use Symfony\Component\HttpFoundation\Request;

final class BesoinController extends AbstractController
{
    #[Route('/admin/besoins', name: 'admin_besoin_list')]
    public function listeBesoins(EntityManagerInterface $emi): Response
    {
        try {
            $besoins = $emi->getRepository(Besoin::class)->findAllBesoins();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('besoin/liste_besoin.html.twig', [
            'controller_name' => 'BesoinController',
            'besoins' => $besoins
        ]);
    }

    #[Route('/user/{id}/besoins', name: 'liste_besoin_id')]
    public function listeBesoinByClientId(EntityManagerInterface $emi, string $id): Response
    {
        try {
            $besoins = $emi->getRepository(Besoin::class)->findAllBesoinsByClientId($id);
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($besoin);
            $entityManager->flush();

            return $this->redirectToRoute('create_besoin');
        }

        return $this->render('besoin/create_besoin.html.twig', [
            'controller_name' => 'BesoinController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/besoin/{id}/edit', name: 'edit_besoin')]
    public function edit(Besoin $besoin, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BesoinType::class, $besoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('edit_besoin', ['id' => $besoin->getId()]);
        }

        return $this->render('besoin/edit_besoin.html.twig', [
            'besoin' => $besoin,
            'form' => $form->createView(),
        ]);
    }
}
