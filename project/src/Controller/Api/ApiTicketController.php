<?php

namespace App\Controller\Api;

use App\Entity\Seat;
use App\Entity\Session;
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
        /**@var Session $session */
        $session = $this->getEntityFromRequest('sessionId', $request, $sessionRepository);
        /**@var Seat $seat */
        $seat = $this->getEntityFromRequest('seatId', $request, $seatRepository);

        if ($session && $seat && $seat->hasTicket($session)) {
            $ticket = (new Ticket())
                ->setSeat($seat)
                ->setSession($session);

            $em = $managerRegistry->getManager();

            $em->persist($ticket);
            $em->flush();

            return $this->render('ticket/new_ticket.html.twig', compact('ticket'));
        }

        return $this->createNotFoundException();
    }

    private function getEntityFromRequest(string $key, Request $request, $repository)
    {
        if ($id = $request->request->get($key)) {
            if ($entity = $repository->find($id)) {
                return $entity;
            }
        }

        return null;
    }
}