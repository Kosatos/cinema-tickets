<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtantion extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('film_interval', [$this, 'convertToTime']),
        ];
    }

    public function convertToTime(string $time): string
    {
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d ' . $time);
        $diff = strtotime($to) - strtotime($from);
        $minutes = $diff / 60;

        return (int)$minutes . " минут(ы)";
    }
}