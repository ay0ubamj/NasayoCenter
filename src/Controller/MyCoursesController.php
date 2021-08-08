<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyCoursesController extends AbstractController
{
    #[Route('/my-courses/learning', name: 'my-courses')]
    public function index(): Response
    {
        return $this->render('my-courses/index.html.twig');
    }
}
