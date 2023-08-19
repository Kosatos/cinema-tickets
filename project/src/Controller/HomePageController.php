<?php

namespace App\Controller;

use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CinemaRepository $cinemaRepository): Response
    {
        $films = $cinemaRepository->findAll();

        return $this->render('pages/home.html.twig', compact('films'));
    }
}
