<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route(name: 'app_home')]
    public function index(
        GameRepository $gameRepository,
        ReviewRepository $reviewRepository,
        CategoryRepository $categoryRepository
    ): Response {

        $lastGames = $gameRepository->findBy(
            [],
            ['publishedAt' => 'DESC'],
            9
        );
        $expensiveGames = $gameRepository->findBy(
            [],
            ['price' => 'DESC'],
            9
        );
        $bestSellers = $gameRepository->findByBestSeller(9);

        $bestTimePlayGames = $gameRepository->findByBestTimePlayGame(9);

        $lastReviews = $reviewRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            4
        );

        $bestPlayCategories = $categoryRepository->findBestPlayCategories(9);

        //        $bestPlayCategories = $categoryRepository->findBestPlayCategories(9);

        return $this->render('home/index.html.twig', [
            'lastGames' => $lastGames,
            'expensiveGames' => $expensiveGames,
            'bestSellers' => $bestSellers,
            'bestTimePlayGames' => $bestTimePlayGames,
            'lastReviews' => $lastReviews,
            //            'bestCategories' => $bestCategories,
            'bestPlayCategories' => $bestPlayCategories,
        ]);
    }
}
