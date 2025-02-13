<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Besoin;
use App\Entity\User;
use App\Form\BesoinType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class BesoinController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/admin/besoins', name: 'admin_besoin_list')]
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

            $user = $this->tokenStorage->getToken()->getUser();
            $besoin->setClient($user);

            $entityManager->persist($besoin);
            $entityManager->flush();

            return $this->redirectToRoute('liste_besoin_id', ['id' => $user->getId()->toString()]);
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
            $user = $this->tokenStorage->getToken()->getUser();

            $entityManager->flush();

            return $this->redirectToRoute('liste_besoin_id', ['id' => $user->getId()->toString()]);
        }

        return $this->render('besoin/edit_besoin.html.twig', [
            'besoin' => $besoin,
            'form' => $form->createView(),
        ]);
    }
}
