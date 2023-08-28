<?php

namespace App\Twig;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtantion extends AbstractExtension
{
	public function getFunctions(): array
	{
		return [
			new TwigFunction('film_interval', [$this, 'convertToTime']),
			new TwigFunction('currentWeekCollection', [$this, 'getCurrentWeek']),
			new TwigFunction('convertMinutes', [$this, 'getConvertMinutes']),
		];
	}

	# TODO написать нормально (оптимизировать) 11 минут / 12 минут
	public function getConvertMinutes(int $minutes): string
	{
		$mapped = [
			'1' => 'минута',
			'2' => 'минуты',
			'3' => 'минуты',
			'4' => 'минуты',
			'5' => 'минут',
			'6' => 'минут',
			'7' => 'минут',
			'8' => 'минут',
			'9' => 'минут',
			'0' => 'минут'
		];
		$data = substr((string)$minutes, -1);

		return $mapped[$data];
	}

	public function convertToTime(string $time): string
	{
		$mapped = [
			'1' => 'минута',
			'2' => 'минуты',
			'3' => 'минуты',
			'4' => 'минута',
			'5' => 'минут',
			'6' => 'минут',
			'7' => 'минут',
			'8' => 'минут',
			'9' => 'минут',
			'0' => 'минут'
		];



		$from    = date('Y-m-d 00:00:00');
		$to      = date('Y-m-d ' . $time);
		$diff    = strtotime($to) - strtotime($from);
		$minutes = $diff / 60;

		$data = substr((string)$minutes, -1);


		return (int)$minutes . " {$mapped[$data]}";
	}

	public function getCurrentWeek(): array
	{
		$translater = [
			'Mon' => 'Пн',
			'Tue' => 'Вт',
			'Wed' => 'Ср',
			'Thu' => 'Чт',
			'Fri' => 'Пт',
			'Sat' => 'Сб',
			'Sun' => 'Вс',
		];
		$data       = new DateTimeImmutable();

		$week = [];
		for ($i = 0; $i < 7; $i++) {
			if ($i === 0) {
				$week[] = [
					'date' => $formattedData = $data->format('Y-m-d'),
					'name' => $translater[date('D', strtotime($formattedData))],
					'today' => true,
				];
			} else {
				$week[] = [
					'date' => $formattedData = $data->modify('+' . $i . ' day')->format('Y-m-d'),
					'name' => $translater[date('D', strtotime($formattedData))],
					'today' => false,
				];
			}
		}

		return $week;
	}
}