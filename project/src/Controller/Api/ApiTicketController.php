<?php

namespace App\Controller\Api;

use App\Entity\Seat;
use App\Entity\Ticket;
use App\Repository\SeatRepository;
use App\Repository\SessionRepository;
use App\Service\QrCodeService;
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
                                 SeatRepository    $seatRepository, QrCodeService $codeService): Response|NotFoundHttpException
    {
        if (!$requestContent = json_decode($request->getContent(), true)) {
            return $this->json('данные не полные, либо повреждены', 404);
        }

        $session = $sessionRepository->find($requestContent['sessionId']);
        $seats = array_map(fn(int $id) => $seatRepository->find($id), $requestContent['seatId']);

        if ($session && count($seats) > 0) {
            $em = $managerRegistry->getManager();

            $tickets = [];
            array_map(function (Seat $seat) use ($em, $session, &$tickets, $codeService) {
                if (!$seat->hasTicket($session)) {
                    $ticket = (new Ticket())
                        ->setSeat($seat)
                        ->setSession($session);

                    $em->persist($ticket);
                    $em->flush();
                    $tickets[] = [$ticket, $codeService->resolve($ticket->getFullData())];
                }
            }, $seats);


            return $this->render('components/ticket.html.twig', compact('tickets'));
        }

        return $this->json(false, 400);
    }
}