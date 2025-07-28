<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GameRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GameExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('get_rating_avg', [GameRuntime::class, 'getRatingAvg']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_last_game', [GameRuntime::class, 'getLastGame']),
        ];
    }
}
