<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
	#[Route('/{data}', name: 'homepage')]
	public function index(SessionRepository $sessionRepository, $data = null): Response
	{
		if ($data) {
			$dataFormat = "{$data} 00:01:00";
		} else {
			$data = new DateTimeImmutable();
			$dataFormat = "{$data->format('Y-m-d')} 00:01:00";
		}

		$sessionData =  DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dataFormat);

		$mainSessions = $sessionRepository->findBy(['data' => $sessionData]);

		return $this->render('pages/home.html.twig', compact('mainSessions'));
	}
}
