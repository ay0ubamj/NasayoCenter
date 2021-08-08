<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Commentaire;
use App\Form\CommentsType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\FormationRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class CourseController extends AbstractController
{
    #[Route('/course/{slug}', name: 'course')]
    public function index(Formation $formation, 
        CommentaireRepository $commentaireRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $commentaireRepository->findBy(['formation' => $formation->getId(), 'etat' => true]);
        $commentaires = $paginator->paginate(
            $data, /* Query */
            $request->query->getInt('comments', 1), /* Page number in the URL */
            4 /* Limit of courses */
        );

        // Création du formulaire de commentaire
        $comment = new Commentaire;
        $date = new DateTimeImmutable();

        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $user = $this->getUser();
            $comment->setCommentaire($commentForm->get('commentaire')->getData());
            $comment->setCreatedAt($date);
            $comment->setEtat(false);
            $comment->setUser($user);
            $comment->setFormation($formation);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('course', ['slug' => $formation->getSlug()]);
        }


        return $this->render('course/index.html.twig', [
            'formation' => $formation,
            'commentaires' => $commentaires,
            'nbrCommentaires' => $commentaireRepository->NumberofComments($formation),
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
