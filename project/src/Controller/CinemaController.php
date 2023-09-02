<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Seat;
use App\Entity\Session;
use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CinemaController extends AbstractController
{
    #[Route('/session/{slug}', name: 'app_show_cinema')]
    public function showCinema(?Session $session): Response|NotFoundHttpException
    {
//        $seats = array_map(fn(Seat $seat) => [
//            'seatId' => $seat->getId(),
//            'identifier' => [
//                'row' => $seat->getIdentifier()[0],
//                'place' => $seat->getIdentifier()[1],
//            ],
//            'isVip' => $seat->isIsVip(),
//            'hasTicket' => $seat->hasTicket($session, $seat),
//        ], $session->getHall()->getSeats()->toArray());


        $seats = $session->getHall()->getSeats();

        return $this->render('pages/session.html.twig', compact('session', 'seats'));
    }
}
