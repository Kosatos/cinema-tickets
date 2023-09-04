<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session/{slug}', name: 'app_show_cinema')]
    public function showCinema(?Session $session): Response
    {
        $seats = $session->getHall()->getSeats();

        return $this->render('pages/session.html.twig', compact('session', 'seats'));
    }
}
