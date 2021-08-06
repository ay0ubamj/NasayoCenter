<?php

namespace App\Controller;

use App\Entity\Formation;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\FormationRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'courses')]
    public function index(
        FormationRepository $formationRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $formationRepository->findAll();

        $formations = $paginator->paginate(
            $data, /* Query */
            $request->query->getInt('page', 1), /* Page number in the URL */
            8 /* Limit of courses */
        );

        return $this->render('courses/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    
}
