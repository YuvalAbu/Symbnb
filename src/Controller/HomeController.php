<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(AnnonceRepository $annonceRepo, UserRepository $userRepo)
    {
        return $this->render('home/home.html.twig',[
            'annonces' => $annonceRepo->findBestAnnonces(3),
            'users' => $userRepo->findBestUsers(2),
        ]);
    }
}
