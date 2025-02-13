<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;
use App\Form\BesoinType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

final class BesoinController extends AbstractController
{
    #[Route('/besoins', name: 'liste_besoin')]
    public function listeBesoins(EntityManagerInterface $emi, PaginatorInterface $paginator, Request $request): Response
    {
        try {
            $query = $emi->getRepository(Besoin::class)->createQueryBuilder('b')->getQuery();
            $page = $request->query->getInt('page', 1);
            $pagination = $paginator->paginate($query, $page, 5);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('besoin/liste_besoin.html.twig', [
            'controller_name' => 'BesoinController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/besoins/{id}', name: 'liste_besoin_id')]
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
