<?php

namespace App\Controller;

use App\Entity\Session;
use DateTimeImmutable;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/session/{data}', name: 'homepage')]
    public function index(SessionRepository $sessionRepository, $data = null): Response
    {
        $sessionsAll = $sessionRepository->findAll();

        if (null == $data) {
            $data = (new DateTimeImmutable())->format('Y-m-d');
        }

        $sessions = array_filter($sessionsAll, function (Session $session) use ($data) {
            return $session->getData()->format('Y-m-d') === $data;
        });

        return $this->render('pages/home.html.twig', compact('sessions'));
    }
}
