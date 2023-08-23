<?php

namespace App\Controller;

use App\Entity\Cinema;
use DateTimeImmutable;
use App\Entity\Session;
use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
	#[Route('/', name: 'homepage')]
	public function index(CinemaRepository $cinemaRepository): Response
	{
		$data = (new DateTimeImmutable())->format('Y-m-d');

		$films = array_filter($cinemaRepository->findAll(), fn(Cinema $cinema) => $cinema
				->getSessions()
				->filter(fn(Session $session) => $session->getData()->format('Y-m-d') === $data)->count() > 0
		);

		return $this->render('pages/home.html.twig', compact('films'));
	}
}
