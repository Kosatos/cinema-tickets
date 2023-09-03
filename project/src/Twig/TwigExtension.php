<?php

namespace App\Twig;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('film_interval', [$this, 'convertToTime']),
            new TwigFunction('currentWeekCollection', [$this, 'getCurrentWeek']),
            new TwigFunction('convertMinutes', [$this, 'getConvertMinutes']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('base64', [$this, 'twigBase64Filter']),
        ];
    }

    function twigBase64Filter(string $source): string
    {
        return base64_encode($source);
    }

    public function convertToTime(string $time): string
    {
        $textCollection = ['минутa', 'минуты', 'минут'];

        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d ' . $time);
        $diff = strtotime($to) - strtotime($from);
        $minutes = $diff / 60;

        $number = (int)substr((string)$minutes, -1);

        $text = match (($number >= 20) ? $number % 10 : $number) {
            1 => $textCollection[0],
            2, 3, 4 => $textCollection[1],
            default => $textCollection[2],
        };

        return (int)$minutes . " $text";
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
        $data = new DateTimeImmutable();

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