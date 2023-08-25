<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Cinema;
use DateTimeImmutable;
use App\Entity\Session;
use App\Repository\HallRepository;
use App\Repository\CinemaRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #TODO перенести логику поиска залов для фильмов, и сессий для залов в репозитории... потренироваться нужно будет
    /**
     * @throws Exception
     */
    #[Route('/', name: 'homepage')]
    public function index(Request $request, CinemaRepository $cinemaRepository, HallRepository $hallRepository): Response
    {
        if ($request->query->has('films')) {
            $data = $request->query->get('films');
        } else {
            $data = (new DateTimeImmutable())->format('Y-m-d');
        }

        $currentDataFilms = array_filter(
            $cinemaRepository->findAll(),
            fn(Cinema $cinema) => $cinema->getSessions()->filter(fn(Session $session) => $session->getData()->format('Y-m-d') === $data)->count() > 0
        );

        $currentDataSessionHalls = array_filter(
            $hallRepository->findAll(),
            fn(Hall $hall) => array_filter($hall->getSessions()->toArray(), fn(Session $session) => $session->getHall()->getNumber() == $hall->getNumber() && $session->getData()->format('Y-m-d') == $data)
        );

        $films = array_map(fn(Cinema $cinema) => [
            'cinema' => $cinema,
            'halls' => array_map(fn(Hall $hall) => [
                'hall' => $hall->getNumber(),
                'sessions' => array_filter($hall->getSessions()->toArray(), fn(Session $session) => $session->getHall()->getNumber() == $hall->getNumber() && $session->getData()->format('Y-m-d') == $data)
            ],
                $currentDataSessionHalls),
        ], $currentDataFilms);

        if ($request->isXmlHttpRequest()) {
            return $this->render('components/session/sessions-list.html.twig', compact('films'));
        }

        return $this->render('pages/home.html.twig', compact('films'));
    }
}