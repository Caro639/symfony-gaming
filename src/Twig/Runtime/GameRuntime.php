<?php

namespace App\Twig\Runtime;

use App\Entity\Game;
use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

class GameRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private GameRepository $gameRepository
    ) {
        // Inject dependencies if needed
    }

    public function getRatingAvg(Game $game): float
    {
        if (count($game->getReviews()) > 0) {

            $sum = 0;
            foreach ($game->getReviews() as $review) {
                $sum += $review->getRating();
            }
            return $sum / count($game->getReviews());
        }
        return 0;
    }

    // ajouter les typage objet ou null si vide
    public function getLastGame(): Game|null
    {
        $game = $this->gameRepository->findOneBy([], ['publishedAt' => 'DESC']);
        // dd($game);
        return $game;
    }
}
