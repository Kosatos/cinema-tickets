<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Session;
use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CinemaController extends AbstractController
{
//    #[Route('/cinema', name: 'app_cinema')]
//    public function index(CinemaRepository $cinemaRepository): Response
//    {
//        $films = $cinemaRepository->findAll();
//
//        return $this->render('cinema/index.html.twig', compact('films'));
//    }

    #[Route('/session/{slug}', name: 'app_show_cinema')]
    public function showCinema(?Session $session): Response|NotFoundHttpException
    {
        return $this->render('pages/session.html.twig', compact('session'));
    }
}
