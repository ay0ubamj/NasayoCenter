<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileInformationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/information', name: 'profile-info')]
    public function changeInformation(Request $request): Response
    {
        // Création du formulaire
        $user = $this->getUser();
        $userInfoForm = $this->createForm(ProfileInformationFormType::class, $user);
        $userInfoForm->handleRequest($request);

        if ($userInfoForm->isSubmitted() && $userInfoForm->isValid()) {
            $user->setNom($userInfoForm->get('nom')->getData());
            $user->setPrenom($userInfoForm->get('prenom')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'The information has been updated successfully !');
            return $this->redirectToRoute('profile-info');
        }
        

        return $this->render('profile/profileInformation.html.twig', [
            'profileInfoForm' => $userInfoForm->createView(),
        ]);
    }

    #[Route('/profile/{id}{nom}/', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
