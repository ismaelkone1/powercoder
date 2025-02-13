<?php

namespace App\Controller;

use App\Entity\Salarie;
use App\Form\SalarieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionSalariesController extends AbstractController
{
    #[Route('/admin/salaries', name: 'app_gestion_salaries')]
    public function listeSalaries(EntityManagerInterface $emi): Response
    {
        try {
            $salaries = $emi->getRepository(Salarie::class)->findAll();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('gestion_salaries/liste_salaries.html.twig', [
            'salaries' => $salaries
        ]);
    }

    #[Route('/admin/salaries/create', name: 'app_gestion_salaries_create')]
    public function createSalarie(Request $request, EntityManagerInterface $emi): Response
    {
        $salarie = new Salarie();
        $form = $this->createForm(SalarieType::class, $salarie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emi->persist($salarie);
            $emi->flush();

            return $this->redirectToRoute('app_gestion_salaries');
        }

        return $this->render('gestion_salaries/create_salarie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
