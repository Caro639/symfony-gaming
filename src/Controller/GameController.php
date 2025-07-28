<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use App\Repository\PublisherRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{

    #[Route('/jeux/{slug}', name: 'app_show_game', methods: ['GET', 'POST'])]
    public function show(
        string $slug,
        GameRepository $gameRepository,
        CountryRepository $countryRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        EntityManagerInterface $manager,
        ReviewRepository $reviewRepository,
        PublisherRepository $publisherRepository,
        UserRepository $userRepository
    ): Response {

        $game = $gameRepository->findOneBy(['slug' => $slug]);
        if (empty($game)) {
            //             Utilisable seulement dans un controller !
            $this->addFlash('danger', 'Ce jeu n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }
        dump($game);

        $countries = $countryRepository->findByCountries($game, 9);

        $categoryByGame = $categoryRepository->findByGameCategory($game, 9);

        $ratingByGame = $reviewRepository->findAverageRatingByGame($game);

        dump($ratingByGame);

        $publisherByGame = $publisherRepository->findPublisherByGame($game);

        $reviewByGame = $reviewRepository->findReviewByGame($game, 9);
        // dump($reviewByGame);

        // ajouter un avis review sur le jeu
        $user = $this->getUser();

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setGame($game);
            $review->setUser($user);
            $review->setCreatedAt(new \DateTimeImmutable());

            //            dd($review);
            $manager->persist($review);
            $manager->flush();
            $this->addFlash('success', 'Votre avis a été ajouté avec succès !');

            return $this->redirectToRoute('app_show_game', [
                'slug' => $game->getSlug()
            ]);
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'countries' => $countries,
            'findByGameCategory' => $categoryByGame,
            'findAverageRatingByGame' => $ratingByGame,
            'findPublisherByGame' => $publisherByGame,
            'findReviewByGame' => $reviewByGame,
            'formReview' => $form,
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_game_category')]
    public function gameCategory(
        string $slug,
        CategoryRepository $categoryRepository,
        GameRepository $gameRepository
    ): Response {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        if ($category === null) {
            $this->addFlash('danger', 'Cette catégorie n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        $gamesByCategory = $gameRepository->findByCategory($category, 9);
        if (empty($gamesByCategory)) {
            $this->addFlash('danger', 'Aucun jeu trouvé dans cette catégorie.');
            return $this->redirectToRoute('app_home');
        }

        dump($gamesByCategory);

        return $this->render('game/games_by_category.html.twig', [
            'category' => $category,
            'gamesByCategory' => $gamesByCategory,
        ]);
    }

}
