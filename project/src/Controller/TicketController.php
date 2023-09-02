<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route('/ticket/{code}', name: 'app_ticket')]
    public function showTicket(?Ticket $ticket): Response
    {
        if ($ticket) {
            return $this->render('ticket/ticket.html.twig', [
                'ticket' => $ticket,
            ]);
        }

        return new Response('Error, ticket not found',404);
    }
}
