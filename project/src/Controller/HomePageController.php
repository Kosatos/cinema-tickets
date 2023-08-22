<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Repository\CinemaRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
	#[Route('/', name: 'homepage')]
	public function index(SessionRepository $sessionRepository): Response
	{
		$data = new DateTimeImmutable();
		$sessionData = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "{$data->format('Y-m-d')} 00:01:00");

		$mainSessions = $sessionRepository->findBy(['data' => $sessionData]);

		return $this->render('pages/home.html.twig', compact('mainSessions'));
	}
}
