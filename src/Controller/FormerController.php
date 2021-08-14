<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormerController extends AbstractController
{
    #[Route('/former/{id}', name: 'former')]
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('former/index.html.twig', [
            'formerCourses' => $formationRepository->findBy(['formateur' => $this->getUser()->getId()]),
        ]);
    }
}
