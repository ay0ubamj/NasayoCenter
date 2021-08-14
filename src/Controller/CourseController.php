<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Formation;
use App\Form\CommentsType;
use App\Entity\Commentaire;
use App\Form\SubscribeCourseFormType;
use App\Form\UnsubscribeCourseFormType;
use App\Repository\FormationRepository;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    #[Route('/course/{slug}', name: 'course')]
    public function index(
        Formation $formation,
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
        $user = $this->getUser();

        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

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

        // Users in the course
        $courseUsers = $formation->getUser();

        // SubscribeInCourse
        $SubcourseForm = $this->createForm(SubscribeCourseFormType::class, $formation);
        $SubcourseForm->handleRequest($request);

        if ($SubcourseForm->isSubmitted() && $SubcourseForm->isValid()) {
            if(!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){
                $em = $this->getDoctrine()->getManager();

                $user->addFormation($formation);
                $formation->addUser($user);
    
                $em->persist($user);
                $em->persist($formation);
                $em->flush();

                
                $this->addFlash('message', 'You are registred now in this course !');
                return $this->redirectToRoute('course', ['slug' => $formation->getSlug()]);
            }

        }

        // UnSubscribeInCourse
        $UnSubcourseForm = $this->createForm(UnsubscribeCourseFormType::class, $formation);
        $UnSubcourseForm->handleRequest($request);

        if ($UnSubcourseForm->isSubmitted() && $UnSubcourseForm->isValid()) {
            
            $em = $this->getDoctrine()->getManager();

            $user->removeFormation($formation);
            $formation->removeUser($user);

            $em->persist($user);
            $em->persist($formation);
            $em->flush();

            $this->addFlash('message', 'You are no longer registred in this course !');
            return $this->redirectToRoute('course', ['slug' => $formation->getSlug()]);
        }

        return $this->render('course/index.html.twig', [
            'formation' => $formation,
            'commentaires' => $commentaires,
            'nbrCommentaires' => $commentaireRepository->NumberofComments($formation),
            'commentForm' => $commentForm->createView(),
            'SubForm' => $SubcourseForm->createView(),
            'UnSubForm' => $UnSubcourseForm->createView(),
            'courseUsers' => $courseUsers,
        ]);
    }
}
