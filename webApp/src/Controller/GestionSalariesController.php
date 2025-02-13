<?php

namespace App\Controller;

use App\Entity\Salarie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
