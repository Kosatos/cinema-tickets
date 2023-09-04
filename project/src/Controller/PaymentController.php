<?php

namespace App\Controller;

use App\Repository\SeatRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment')]
    public function resolvePayment(Request $request, SessionRepository $sessionRepository, SeatRepository $seatRepository): Response
    {
        if ($request->query->has('sessionId')) {
            $sessionId = $request->query->get('sessionId');
            $session = $sessionRepository->find($sessionId);
        }

        if ($request->query->has('seat')) {
            $seatCollectionId = explode(',', $request->query->get('seat'));
            $seats = array_map(fn($id) => $seatRepository->find($id), $seatCollectionId);
        }

        if (isset($session) && isset($seats)) {
            return $this->render('pages/payment.html.twig', compact('seats', 'session'));
        }

        return $this->json(['error' => 'data is destroyed'], 400);
    }
}
