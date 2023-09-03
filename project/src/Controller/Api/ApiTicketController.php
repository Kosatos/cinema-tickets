<?php

namespace App\Controller\Api;

use App\Entity\Ticket;
use App\Repository\SeatRepository;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiTicketController extends AbstractController
{
    #[Route('/ticket', methods: ['POST'])]
    public function createTicket(ManagerRegistry   $managerRegistry,
                                 Request           $request,
                                 SessionRepository $sessionRepository,
                                 SeatRepository    $seatRepository): Response|NotFoundHttpException
    {
        if (!$requestContent = json_decode($request->getContent(), true)) {
            return $this->json('данные не полные, либо повреждены', 404);
        }

        $session = $sessionRepository->find($requestContent['sessionId']);
        $seat = $seatRepository->find($requestContent['seatId']);

        if ($session && $seat && !$seat->hasTicket($session)) {
            $ticket = (new Ticket())
                ->setSeat($seat)
                ->setSession($session);

            $em = $managerRegistry->getManager();

            $em->persist($ticket);
            $em->flush();

            return $this->json([
                'status' => 'success',
                'code' => $ticket->getCode(),
                'film' => $ticket->getCinema(),
                'row' => $ticket->getSeat()->getIdentifier()[0],
                'place' => $ticket->getSeat()->getIdentifier()[1],
                'hall' => $ticket->getHall(),
                'session' => $ticket->getSession()->getData()->format('Y-m-d H:i:s'),
                'isVip' => $ticket->getSeat()->isIsVip(),
            ]);
        }

        return $this->json(false, 400);
    }
}