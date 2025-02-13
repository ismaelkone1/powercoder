<?php

namespace App\Controller;

use App\Entity\Competence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CompetenceType;

#[Route('/admin/competences', name: 'admin_competence_')]
final class CompetenceController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $emi): Response
    {
        $competences = $emi->getRepository(Competence::class)->findAll();

        return $this->render('competence/index.html.twig', [
            'competences' => $competences,
        ]);
    }

    #[Route('/create', name: 'new')]
    public function creerCompetence(Request $request, EntityManagerInterface $emi): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $competences_type = $emi->getRepository(Competence::class)->findBy(['type' => $competence->getType()]);
            if ($competences_type) {
                $this->addFlash('danger', 'Le type de compétence existe déjà.');
                return $this->redirectToRoute('admin_competence_new');
            }
            $competence->setType(strtoupper($competence->getType()));

            $emi->persist($competence);
            $emi->flush();

            $this->addFlash('success', 'La compétence a été créée avec succès.');
            return $this->redirectToRoute('admin_competence_index');
        }

        return $this->render('competence/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function editerCompetence(Request $request, Competence $competence, EntityManagerInterface $emi): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $competences_type = $emi->getRepository(Competence::class)->findBy(['type' => $competence->getType()]);

            if ($competences_type) {
                $this->addFlash('danger', 'Le type de compétence existe déjà.');
                return $this->redirectToRoute('admin_competence_edit', ['id' => $competence->getId()]);
            }
            $competence->setType(strtoupper($competence->getType()));

            $emi->flush();

            $this->addFlash('success', 'La compétence a été modifiée avec succès.');
            return $this->redirectToRoute('admin_competence_index');
        }

        return $this->render('competence/edit.html.twig', [
            'competence' => $competence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function supprimerCompetence(Request $request, Competence $competence, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($competence);
        $entityManager->flush();

        $this->addFlash('success', 'La compétence a été supprimée avec succès.');

        return $this->redirectToRoute('admin_competence_index');
    }
}
