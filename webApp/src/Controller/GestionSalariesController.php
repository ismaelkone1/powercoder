<?php

namespace App\Controller;

use App\Entity\SalarieCompetence;
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
        public function createSalarie(Request $request, EntityManagerInterface $entityManager): Response
        {
            $salarie = new Salarie();
            $form = $this->createForm(SalarieType::class, $salarie);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $selectedCompetences = $form->get('selectedCompetences')->getData();
                $interet = $form->get('interet')->getData();
        
                $entityManager->persist($salarie);
                
                foreach ($selectedCompetences as $competence) {
                    $salarieCompetence = new SalarieCompetence();
                    $salarieCompetence->setSalarie($salarie);
                    $salarieCompetence->setCompetence($competence);
                    $salarieCompetence->setInteret($interet);
                    $entityManager->persist($salarieCompetence);
                }
        
                $entityManager->flush();
        
                $this->addFlash('success', 'Salarié créé avec succès');
                return $this->redirectToRoute('app_gestion_salaries');
            }
        
            return $this->render('gestion_salaries/create_salarie.html.twig', [
                'form' => $form->createView(),
            ]);
        }       
}
