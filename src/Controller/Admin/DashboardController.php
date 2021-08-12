<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Formation;
use App\Entity\Commentaire;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    protected $userRepository;
    protected $formationRepository;
    protected $commentaireRepository;

    public function __construct(UserRepository $userRepository, FormationRepository $formationRepository, CommentaireRepository $commentaireRepository)
    {
        $this->userRepository = $userRepository;
        $this->formationRepository = $formationRepository;
        $this->commentaireRepository = $commentaireRepository;
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [
            'allUsers' => $this->userRepository->CountUsers(),
            'allCourses' => $this->formationRepository->CountFormations(),
            'lastCourses' => $this->formationRepository->lastTree(),
            'allComments' => $this->commentaireRepository->CountComments()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nasayo Center')
            ->renderContentMaximized();
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getFullName())
            ->setGravatarEmail($user->getEmail());
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Courses', 'fas fa-graduation-cap', Formation::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Commentaire::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
    }
}
