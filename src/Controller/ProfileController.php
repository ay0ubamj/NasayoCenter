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

            return $this->redirectToRoute('profile-info');
        }
        

        return $this->render('profile/profileInformation.html.twig', [
            'user' => $user,
            'profileInfoForm' => $userInfoForm->createView(),
        ]);
    }

    #[Route('/profile/{id}{nom}/', name: 'profile')]
    public function profile(User $user): Response
    {
        $userFormations = $user->getFormations();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'userFormations' => $userFormations,
        ]);
    }
}
